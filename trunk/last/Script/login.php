<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 **/
if (!SCRIPT_IN) die('Need by included');

$validsession=false; // false=nul, true=Ok, -1=Err?
$login_msg = ''; // Message en cas d'erreur.

/// ### Procédure de déconnexion ###
if (isset($do_logout) && $do_logout) {
    $_SESSION['_login'] = $_SESSION['_pass'] = $_SESSION['_Perm'] = $_SESSION['_IP'] = '';
    output::Boink(ROOT_URL);
}

if (NO_SESSIONS) {
    $login = sqlesc(strtolower($_POST['user']));
    $mdp = $_POST['pass'];

    $query = "SELECT LOWER(Login) as Login,Permission, m.carte_prefs from SQL_PREFIX_Users, SQL_PREFIX_Membres m WHERE LOWER(Login)=LOWER('$login') AND Password='$mdp' AND (m.Joueur=LOWER('$login'))";

    $mysql_result = DataEngine::sql($query);
    $ligne=mysql_fetch_assoc($mysql_result);

    if(is_array($ligne) && $ligne['Login'] == $login) { // session valide
        $validsession=true;
        $_SESSION['_login'] = $login;
        $_SESSION['_pass']  = $mdp;
        $_SESSION['_Perm']  = $ligne['Permission'];
        $_SESSION['carte_prefs']  = $ligne['carte_prefs'];
        $_SESSION['_IP']  	= Get_IP();
        $_SESSION['_permkey']  = sha1($mdp.$_SESSION['_IP']);
//        $query = "INSERT INTO SQL_PREFIX_Log (DATE,LOGIN,IP) VALUES(NOW(),'$login','{$_SESSION['_IP']}')";
//        DataEngine::sql($query);
        return true;
    } else { // login/pass pas bon...
        $query = "INSERT INTO SQL_PREFIX_Log (DATE,LOGIN,IP) VALUES(NOW(),'err.eude:$login','".Get_IP()."')";
        DataEngine::sql($query);
        header('HTTP/1.1 403 Forbidden');
        $out = <<<o
<eude>
    <alert>Mot de passe ou nom d'utilisateur incorrect</alert>
    <GM_active>0</GM_active>
    <logtype>raid</logtype>
    <log>Identification au Data Engine échoué.</log>
</eude>
o;
        output::_DoOutput($out);
    }

}

// Procédure identification...
if($_POST && !empty($_POST['login']) && !empty($_POST['mdp'])) {
// Récup du login/pass...
    $login = sqlesc(strtolower($_POST['login']));
    $mdp = md5($_POST['mdp']);

    $query = "SELECT LOWER(Login) as Login,Permission, m.carte_prefs from SQL_PREFIX_Users, SQL_PREFIX_Membres m WHERE LOWER(Login)=LOWER('$login') AND Password='$mdp' AND (m.Joueur=LOWER('$login'))";

    $mysql_result = DataEngine::sql($query);
    $ligne=mysql_fetch_assoc($mysql_result);

    if(is_array($ligne) && $ligne['Login'] == $login) { // session valide
        $validsession=true;
        $_SESSION['_login'] = $login;
        $_SESSION['_pass']  = $mdp;
        $_SESSION['_Perm']  = $ligne['Permission'];
        $_SESSION['carte_prefs']  = $ligne['carte_prefs'];
        $_SESSION['_IP']  	= Get_IP();
        $_SESSION['_permkey']  = sha1($mdp.$_SESSION['_IP']);
        $query = "INSERT INTO SQL_PREFIX_Log (DATE,LOGIN,IP) VALUES(NOW(),'$login','{$_SESSION['_IP']}')";
        DataEngine::sql($query);
    } else { // login/pass pas bon...
        $validsession=-1;
        $login_msg = "Mot de passe ou nom d'utilisateur incorrect";
        $query = "INSERT INTO SQL_PREFIX_Log (DATE,LOGIN,IP) VALUES(NOW(),'Err:$login','".Get_IP()."')";
        DataEngine::sql($query);
    }
}

// Vérification de session, si existante et si elle viens pas d'être validé ;)
if( ($validsession===false) && isset($_SESSION['_login']) && $_SESSION['_login'] != "" ) {
    $login = $_SESSION['_login'];
    $mdp = $_SESSION['_pass'];

    $query = "SELECT LOWER(Login) as Login,Permission, m.carte_prefs from SQL_PREFIX_Users, SQL_PREFIX_Membres m WHERE LOWER(Login)=LOWER('$login') AND Password='$mdp' AND (m.Joueur=LOWER('$login'))";
    $mysql_result = DataEngine::sql($query);// or mysql_die($query,__file__,__line__);
    $ligne=mysql_fetch_array($mysql_result);
    if($ligne['Login'] == $login && $_SESSION['_IP'] == Get_IP()) {
        $validsession=true;
        $_SESSION['_Perm']  = $ligne['Permission']; // Maj les permission en cas de changement
        $_SESSION['carte_prefs']  = $ligne['carte_prefs'];
    } else {
        $validsession=-1;
        $query = "INSERT INTO SQL_PREFIX_Log (DATE,LOGIN,IP) VALUES(NOW(),'invalid:$login/{$_SESSION['_Perm']}/{$_SESSION['_IP']}','".Get_IP()."')";
        $_SESSION['_login'] = $_SESSION['_pass'] = $_SESSION['_Perm'] = $_SESSION['_IP'] = ''; // déconnexion...
        DataEngine::sql($query);
    }
}

// Message d'erreur par défaut...
if ( $login_msg =='' && $validsession == -1 ) $login_msg = 'Session invalide !';

if ( $validsession !== true && USE_AJAX ) {
    header('Content-Type: text/xml;charset=utf-8');
    header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
    header('Expires: Mon, 16 Jul 2008 04:21:44 GMT'); // HTTP/1.0 Date dans le passé

    echo '<session>';
    echo "<error><![CDATA[$login_msg]]></error>";
    // execution javascript si parsing xml
    echo '<script><![CDATA[alert(\'Session perdue !\'); location.href=\'?\';]]></script>';
    echo '</session>';
    exit;
}

if ( $validsession !== true && IS_IMG ) {
    header("Content-Type: image/png");
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Mon, 16 Jul 2008 04:21:44 GMT"); // HTTP/1.0 Date dans le passé

    require_once(CLASS_PATH.'map.class.php');
    $Taille		= 180;
    $image 		= imagecreate($Taille,20);
    $background_color = imagecolorallocate ($image, 0, 0, 0);
    imagefilledrectangle($image, 0, 0, $Taille, $Taille, $background_color);
    $debug_cl = imagecolorallocate ($image, 254, 254, 254);
    map::map_debug('Session invalide !');
    imagepng($image);
    imagedestroy($image);
    exit();
}
// $validsession

if ($validsession === true && $_SESSION['_Perm'] < AXX_VALIDATING) {
    $query = "INSERT INTO SQL_PREFIX_Log (DATE,LOGIN,IP) VALUES(NOW(),'AXX_VALIDATING:{$_SESSION['_login']}','{$_SESSION['_IP']}')";
    $_SESSION['_login'] = '';
    DataEngine::sql($query);
    output::_DoOutput('<a href="'.GetForumLink().'"><p style="color:red">Vous n\'avez pas la permission d\'utilisation, Demander les acc&egrave;s a votre alliance</p></a>');
}

if ($validsession !==true) {
    require_once(TEMPLATE_PATH.'login.tpl.php');

    $tpl = tpl_login::getinstance();
    $tpl->page_title = 'EU2: DataEngine, Identification';
    $tpl->DoOutput($login_msg);

}








