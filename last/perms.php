<?php
/**
 * @author Alex10336
 * Dernière modification: $Id$
 * @license GNU Public License 3.0 ( http://www.gnu.org/licenses/gpl-3.0.txt )
 * @license Creative Commons 3.0 BY-SA ( http://creativecommons.org/licenses/by-sa/3.0/deed.fr )
 *
 **/
require_once('./init.php');
require_once(INCLUDE_PATH.'Script.php');

DataEngine::CheckPermsOrDie(AXX_ROOTADMIN);
DataEngine::conf_load();

if (isset ($_POST['cxx'])) {
    DataEngine::conf_update('perms', $_POST['cxx']);
    output::Boink('%ROOT_URL%EAdmin.php');
}

$cxx_name = DataEngine::s_cperms();
$axx_name = DataEngine::s_perms();
$axx_name[AXX_DISABLED] = "Désactivé";
$cxx_conf = DataEngine::config('perms');
$axx_num  = count($axx_name);

//FB::info($cxx_name, 'cxx');
//FB::info($axx_name, 'axx');


include_once(TEMPLATE_PATH.'sample.tpl.php');
$tpl = tpl_sample::getinstance();
//$tpl->css_file   = false;
$tpl->page_title = 'EU2: Administration, permissions';


$out = <<<x
<form method="post">
<table class="color_bg table_nospacing">
    <tr class="color_titre">
        <td colspan="2">Élements conserné</td>
        <td>Niveau minimum d'accès</td>
x;

$tpl->PushOutput($out .'</tr>');
$i=0;

// Loop par CXX
foreach ($cxx_name as $cxx_k => $cxx_v) {
    $class = 'color_row'.$i%2;

    if (is_numeric($cxx_k)) {
        $tpl->PushOutput('<tr><td class="color_header" colspan="3">'.$cxx_v.'</td></tr>');
        continue;
    } else {
        $tpl->PushOutput('<tr class="'.$class.'"><td class="color_header">&nbsp;</td><td>'.$cxx_v.'</td>');
        $tpl->PushOutput('<td class="text_center"><select class="'.$class.'" name="cxx['.$cxx_k.']">');
        // loop par AXX
        foreach ($axx_name as $axx_k=>$axx_v) {
            $selected = ($cxx_conf[$cxx_k]==$axx_k) ? ' selected':'';
            $tpl->PushOutput('<option value="'.$axx_k.'"'.$selected.'>'.$axx_v.'</option>');
        }
        $tpl->PushOutput('</select></td>');
    }

    $tpl->PushOutput('</tr>');
    if (is_numeric($cxx_k)) $i=1;
    $i++;
}
$tpl->PushOutput('<tr class="color_header"><td class="text_right" colspan="3"><input class="color_titre" type="submit" value="Enregistrer" /></td>');
$tpl->PushOutput('</table></form>');


$tpl->DoOutput();