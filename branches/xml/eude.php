<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since 1.4.2
 *
 **/


define('NO_SESSIONS',true);	// mode login/pass a chaque requêtes...
define('USE_AJAX',true);	// mode xml
//define('CHECK_LOGIN',false);

require_once('../init.php');
require_once(INCLUDE_PATH.'Script.php');
require_once(CLASS_PATH.'parser.class.php');
require_once(CLASS_PATH.'cartographie.class.php');

if (!DataEngine::CheckPerms(CXX_CARTOGRAPHIE_GREASE)) {
    header('HTTP/1.1 403 Forbidden');
    output::_DoOutput('<eude><alert>Accès refusée</alert><GM_active>0</GM_active></eude>');
}
if (Config::eude_srv()!='' && Config::eude_srv() != $_POST['svr']) {
    header('HTTP/1.1 403 Forbidden');
    output::_DoOutput("<eude><alert>Accès refusée.\nMauvais serveur de jeu.</alert><GM_active>0</GM_active></eude>");
}

$xml = array();
$carto = cartographie::getinstance();


switch ($_GET['act']) {
    case 'config': //-----------------------------------------------------------
        $xml['log'] = 'Initialisation au Data Engine Ok.';
        $xml['logtype'] = 'none';
        $xml['GM_active'] = '1';
        $xml['GM_galaxy_info'] = DataEngine::CheckPerms(CXX_CARTOGRAPHIE_PLAYERS) ? '1':'0';
        break;

    case 'wormhole': //---------------------------------------------------------
        $carto->add_vortex($_POST['IN'], $_POST['OUT'], '', '', 'from Grease addons');
        $xml['log']='Vortex '.$_POST['IN'].' <--> '.$_POST['OUT'];
        break;

    case 'galaxy_info': //------------------------------------------------------

        $page = gpc_esc($_POST['data']);

        preg_match_all('#class="table_entry_onclick".*width="100">(\d+-\d+-\d+-\d+)</td>\n'.
                '.*\n.*width="150">(.*)</td>\n'.
                '.*\n.*width="284">(.*)</td>#',
                $page, $galaxy_info, PREG_SET_ORDER);
        //preg_match_all('#class="table_entry_onclick".*width="100">(\d+-\d+-\d+-\d+)</td>#', $page, $coords, PREG_SET_ORDER);
        //preg_match_all('#class="table_entry_onclick".*width="150">(\w*)</td>#', $page, $noms, PREG_SET_ORDER);
        //preg_match_all('#class="table_entry_onclick".*width="284">(.*)</td>#', $page, $players, PREG_SET_ORDER);

        $nbplanets = 0;
        $SS_A = $del_planet = $curss_info = array();
        for ($i=0,$max=count($galaxy_info);$i<$max;$i++) {
            $nbplanets++;
            // $galaxy_info[$i][1] = coords xxxx-xx-xx-xx
            // $galaxy_info[$i][2] = nom planète
            // $galaxy_info[$i][3] = Nom joueur (et empire) brut
            if (trim($galaxy_info[$i][3]) != '') {
                preg_match_all('#<b>(\w+)</b><br>(.*)#', $galaxy_info[$i][3], $player, PREG_SET_ORDER);
                $joueur = $player[0][1];
                $empire = $player[0][2];
            } else {
                $joueur = '';
                $empire = '';
            }

            if (trim($galaxy_info[$i][3]) == '' && $carto->FormatId($galaxy_info[$i][1], $dummy, $sys) == '') // Planète inoccupée
                $del_planet[] = $sys;
            else
                $SS_A[] = array($galaxy_info[$i][1], $galaxy_info[$i][2], $joueur, $empire);
        }

        // repiquage cartographie->add_solar_ss
        if (count($del_planet)>0) {
            $del_planet = "'".implode("','",$del_planet)."'";
            $query = "DELETE FROM SQL_PREFIX_Coordonnee where Type in (0,5) AND POSIN='{$_POST['ss']}' AND COORDET in ({$del_planet})";
            $array = DataEngine::sql($query);
            if ( ($num = mysql_affected_rows()) > 0)
                $carto->AddInfo($num.' planète(s) devenue inoccupée');
        }

        $query = "SELECT USER,EMPIRE FROM SQL_PREFIX_Coordonnee where POSIN='{$cur_ss}' AND TYPE=0";
        $sql_result = DataEngine::sql($query);
        while ($row = mysql_fetch_assoc($sql_result)) {
            // par nom de joueur
            $curss_info[$row['USER']] = $row['EMPIRE'];
        }


        foreach($SS_A as $v) {
            $result = $carto->add_player($v);
            if ($result) { // uniquement si changement, vide autrement.
                list($dummy, $dummy, $nom, $empire) = $v;
                $nom    = gpc_esc($nom);
                $empire = gpc_esc($empire);
                if (isset($curss_info[$nom])) {
                    if ($curss_info[$nom] != $empire) {
                        $qnom    = sqlesc($nom, true);
                        $qempire = sqlesc($empire, true);
                        $query = "UPDATE SQL_PREFIX_Coordonnee SET `EMPIRE`='{$qempire}',`UTILISATEUR`='{$_SESSION['_login']}' WHERE USER='{$qnom}'";
                        DataEngine::sql($query);
                        $carto->AddInfo('Changement d\'empire du joueur: \''.$nom.'\' ['.mysql_affected_rows().']');
                        unset($curss_info[$nom]);
                    }
                }
            }
        }

        $xml['log']='Ajout du système N°'. $_POST['ss'].' ('.$max.' éléments)';
        break;
    default:
        $xml['log']='Error !';
}

$msg  = $carto->Infos();
$warn = $carto->Warns();
$err  = $carto->Erreurs();

//$info = ($info!='<br/>') ? ''.$info: '';
$msg = ($msg!='' && $warn!='') ? $msg.'<br/>'.$warn: $msg;
$msg = ($msg!='' && $err!='')  ? $msg.'<br/>'.$err : $msg;

$xml['content']= DataEngine::xml_fix51($msg);
$out  = '<eude>';
foreach($xml as $key => $v)
    if ($v != '')
        $out .= '<'.$key.'><![CDATA['.DataEngine::xml_fix51($v).']]></'.$key.'>';
$out .= '</eude>';

output::_DoOutput($out);