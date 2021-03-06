<?php

/**
 * @author Alex10336
 * Dernière modification: $Id$
 * @license GNU Public License 3.0 ( http://www.gnu.org/licenses/gpl-3.0.txt )
 * @license Creative Commons 3.0 BY-SA ( http://creativecommons.org/licenses/by-sa/3.0/deed.fr )
 *
 * */
define('CHECK_LOGIN', false);
require_once('./init.php');
require_once(INCLUDE_PATH . 'Script.php');

if (DataEngine::config_key('config', 'closed'))
    output::Boink ();
if (!DataEngine::config_key('config', 'CanRegister'))
    Members::NoPermsAndDie();

$lng = language::getinstance()->GetLngBlock('login');

$erreur = '';
if (isset($_POST['login']) && $_POST['login'] != '' && $_POST['mdp'] != '') {
    $login = gpc_esc($_POST['login']);
    $qlogin = sqlesc($_POST['login']);
    $pass = md5($_POST['mdp']);

    $query = 'SELECT LOWER(`Login`) as `Login` from `SQL_PREFIX_Users` WHERE LOWER(`Login`)=LOWER(\'' . $qlogin . '\')';
    $mysql_result = DataEngine::sql($query);
    $ligne = mysql_fetch_array($mysql_result);

    if ($ligne['Login'] == $login) { // joueur existe déjà...
        $erreur = $lng['user_exists'];
    } else {
        if (DE_DEMO) {
            $axx = AXX_MEMBER;
            $_SESSION['_login'] = $login;
            $_SESSION['_pass'] = $pass;
            $_SESSION['_Perm'] = $axx;
            $_SESSION['_IP'] = Get_IP();
        } else {
            $axx = AXX_VALIDATING;
            // TODO: Mail admin on event ?
        }
        Members::NewUser($login, $pass, $axx, 0, DataEngine::config_key('config', 'DefaultGrade'));

        $query = 'INSERT INTO `SQL_PREFIX_Log` (`DATE`,`log`,`IP`) VALUES(NOW(),\'login,new:' . $qlogin . '\',\'' . $_SESSION['_IP'] . '\')';
        DataEngine::sql($query);
        output::boink('./', sprintf($lng['user_created'], $login));
    }
}
require_once(TEMPLATE_PATH . 'login.tpl.php');
$tpl = tpl_login::getinstance();
$tpl->page_title = $lng['signin_page_title'];
$tpl->DoOutput($erreur, true);
