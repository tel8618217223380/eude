<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 **/
define('CHECK_LOGIN',false);
require_once('./init.php');
require_once(INCLUDE_PATH.'Script.php');

if (!Config::CanRegister()) DataEngine::NoPermsAndDie();

$erreur = '';
if (isset($_POST['login']) && $_POST['login'] !='' && $_POST['mdp'] != '') {
    $login = sqlesc($_POST['login']);
    $pass  = md5($_POST['mdp']);

    $query = "SELECT LOWER(Login) as Login from SQL_PREFIX_Users WHERE LOWER(Login)=LOWER('$login')";
    $mysql_result = DataEngine::sql($query);
    $ligne=mysql_fetch_array($mysql_result);

    if($ligne['Login'] == $login) { // joueur existe déjà...
        $erreur = 'Joueur existe déjà...';
    } else {
        if (DE_DEMO)
            $axx = AXX_MEMBER;
        else
            $axx = AXX_GUEST;
        DataEngine::NewUser($login, $pass, $axx, 0, Config::GetDefaultGrade());
        $_SESSION['_login'] = $login;
        $_SESSION['_pass']  = $pass;
        $_SESSION['_Perm']  = $axx;
        $_SESSION['_IP']    = Get_IP();
        $query = "INSERT INTO SQL_PREFIX_Log (DATE,LOGIN,IP) VALUES(NOW(),'New:$login','{$_SESSION['_IP']}')";
        DataEngine::sql($query);
        output::boink('./');
    }
}
require_once(TEMPLATE_PATH.'login.tpl.php');
$tpl = tpl_login::getinstance();
$tpl->page_title = "EU2: Inscription";
$tpl->DoOutput($erreur,true);
