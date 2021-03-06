<?php
/**
 * @author Alex10336
 * Dernière modification: $Id$
 * @license GNU Public License 3.0 ( http://www.gnu.org/licenses/gpl-3.0.txt )
 * @license Creative Commons 3.0 BY-SA ( http://creativecommons.org/licenses/by-sa/3.0/deed.fr )
 *
 **/
if (!defined('DE_INIT')) die('Init error');
define('SCRIPT_IN',			true);
require_once(CLASS_PATH.'dataengine.class.php');
require_once(CLASS_PATH.'FirePHP.class.php');	// Debug
require_once(CLASS_PATH.'fb.php');				// Debug
require_once(CLASS_PATH.'browser.class.php');
require_once(CLASS_PATH.'output.class.php');
require_once(CLASS_PATH.'addons.class.php');
require_once(CLASS_PATH.'language.class.php');

function Get_IP() {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif(isset($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
    else
        return $_SERVER['REMOTE_ADDR'];
}

function bulle ($texte,$addover='',$addout='') {
    if(is_array($addover))
        $addover=implode($addover,'');
    if(is_array($addout)) $addout=implode($addout,'');
    $texte=htmlspecialchars(str_replace("\n", '', $texte),ENT_QUOTES,'UTF-8');
    return ("onmouseover='montre(\"".$texte."\");$addover' onmouseout='cache();$addout'");
}

function Get_string($newvalue=array()) {
    parse_str($_SERVER["QUERY_STRING"], $current_get);
    $result = array_merge($current_get, $newvalue);
    return http_build_query($result);
}

/**
 * @param string $value from $_POST/$_GET
 * @param boolean $skip_gpc
 * @return string Value ready for mysql
 */
function sqlesc($value,$skip_gpc=true) {
    if (!get_magic_quotes_gpc() || $skip_gpc) {
        return mysql_real_escape_string($value);
    } else {
        return mysql_real_escape_string(stripslashes($value));
    }
}

function gpc_esc($value) {
    if (!get_magic_quotes_gpc())
        return $value;
    else
        return stripslashes($value);
}


// Rétrocompatibilité avec php < 5.2.0
if (version_compare(PHP_VERSION, '5.2.0', '<')) {
    function p_strlen($string) {
        return strlen($string);
    }
    function p_stripos($haystack, $needle, $offset=NULL) {
        return stripos($haystack, $needle, $offset);
    }
    function p_strripos($haystack, $needle, $offset=NULL) {
        return strripos($haystack, $needle, $offset);
    }
    function p_substr($string, $start, $length=NULL) {
        return substr($string, $start, $length);
    }
} else {
    mb_internal_encoding('utf-8');

    function p_strlen($str/*, $encoding=NULL*/) {
        return mb_strlen($str/*, $encoding*/);
    }
    function p_stripos($haystack, $needle, $offset=NULL/*, $encoding=NULL*/) {
        return mb_stripos($haystack, $needle, $offset/*, $encoding*/);
    }
    function p_strripos ($haystack, $needle, $offset=NULL/*, $encoding=NULL*/) {
        return mb_strripos($haystack, $needle, $offset/*, $encoding*/);
    }

    function p_substr($str, $start, $length=NULL/*, $encoding=NULL*/) {
        return mb_substr($str, $start, $length/*, $encoding*/);
    }
}

function array_fullsqlesc(&$item1, $key) {
    $item1 = '\'' . mysql_escape_string($item1) . '\'';
}


DataEngine::init();

if (CHECK_LOGIN)  require_once(INCLUDE_PATH.'/login.php');

/// ### Mode debug, root admin & dev ONLY ###
FB::setEnabled( /*!IS_IMG &&*/ IN_DEV && Members::CheckPerms(AXX_ROOTADMIN));
FB::info(DataEngine::$browser->getBrowser(),'Browser');