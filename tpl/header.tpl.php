<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
**/
if (!SCRIPT_IN) die('Need by included');

class tpl_header {
    /**
     *
     * @return string html header
     */
	static public function Get_Header() {
		$obj = DataEngine::tpl('');
                $version = DataEngine::Get_Version();

		if ($obj->page_title=="")
			$title = "EU2: Data Engine v{$obj->version}";
		else
			$title = $obj->page_title;

		if ($obj->css_file!="") {
			$css = <<<EOF
		<link rel="stylesheet" type="text/css" href="{$obj->css_file}?{$obj->version}" media="screen" />
EOF;
		} else {
			$css = '';
		}

// <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
//    "http://www.w3.org/TR/html4/loose.dtd">
if (DE_DEMO)
$stats = <<<st
<!-- phpmyvisites -->
<a href="http://st.free.fr/" title="phpMyVisites | Open source web analytics"
onclick="window.open(this.href);return(false);"><script type="text/javascript">
<!--
var a_vars = Array();
var pagename='';

var phpmyvisitesSite = 175980;
var phpmyvisitesURL = "http://st.free.fr/phpmyvisites.php";
//-->
</script>
<script language="javascript" src="http://st.free.fr/phpmyvisites.js" type="text/javascript"></script>
<object><noscript><p>phpMyVisites | Open source web analytics
<img src="http://st.free.fr/phpmyvisites.php" alt="Statistics" style="border:0" />
</p></noscript></object></a>
<!-- /phpmyvisites -->
st;

		return<<<EOF
<html lang="fr">
<head>
<title>{$title}</title>
{$css}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
{$stats}
<script type="text/javascript" src="%INCLUDE_URL%prototype.js?1.6.1"></script>
<script type="text/javascript" src="%INCLUDE_URL%Script.js?{$version}"></script>
<div id="curseur" class="infobulle" style="z-index:7; position:absolute; visibility:hidden; border: 1px solid White; padding: 10px; font-family: Verdana, Arial, Times; font-size: 10px; background-color: #C0C0C0;white-space:nowrap;"></div>

EOF;

	}
}

