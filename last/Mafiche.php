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
require_once(CLASS_PATH.'parser.class.php');

DataEngine::CheckPermsOrDie('PERSO');

if(isset($_POST['importation'])) {
//    $data = file_get_contents('./test/data/mafiche_ff.txt');
    $data = gpc_esc($_POST['importation']);
    $parser = new parser();

    // simple détection...
    if (stripos($parser->GetValueByLabel($data, 'Nom'), $_SESSION['_login']) !== false) {
        $matrix = explode("\n",trim($data));
        $info = array();
        $info['GameGrade'] = trim($matrix[0]);
        $info['Race'] = $parser->GetValueByLabel($data, 'Race');
        $info['Titre'] = $parser->GetValueByLabel($data, 'Titre');
        $info['Commerce'] = $parser->GetValueByLabel($data, 'Commerce');
        $info['Recherche'] = $parser->GetValueByLabel($data, 'Recherche');
        $info['Combat'] = $parser->GetValueByLabel($data, 'Combat');
        $info['Construction'] = $parser->GetValueByLabel($data, 'Construction');
        $info['Economie'] = $parser->GetValueByLabel($data, 'Économie');
        $info['Navigation'] = $parser->GetValueByLabel($data, 'Navigation');
        $info['POINTS'] = DataEngine::strip_number($parser->GetValueByLabelInverted($data, 'Points total'));
        $info['pts_architecte'] = DataEngine::strip_number($parser->GetValueByLabelInverted($data, 'Points architecte'));
        $info['pts_mineur'] = DataEngine::strip_number($parser->GetValueByLabelInverted($data, 'Points mineur'));
        $info['pts_science'] = DataEngine::strip_number($parser->GetValueByLabelInverted($data, 'Points science'));
        $info['pts_commercant'] = DataEngine::strip_number($parser->GetValueByLabelInverted($data, 'Points commerçant'));
        $info['pts_amiral'] = DataEngine::strip_number($parser->GetValueByLabelInverted($data, 'Points amiral'));
        $info['pts_guerrier'] = DataEngine::strip_number($parser->GetValueByLabelInverted($data, 'Points guerrier'));


        foreach ($info as $k => $v) $info[$k] = sqlesc($v, true);

        $query = <<<q
            UPDATE SQL_PREFIX_Membres SET POINTS='%d',
        Economie='%d', Commerce='%d', Recherche='%d', Combat='%d',
        Construction='%s', Navigation='%d', Race='%s',
        Titre='%s', GameGrade='%s', pts_architecte='%d', pts_mineur='%d',
        pts_science='%d', pts_commercant='%d', pts_amiral='%d',
        pts_guerrier='%d', Date=now() WHERE Joueur='%s'
q;
        DataEngine::sql(sprintf($query, $info['POINTS'],
            $info['Economie'], $info['Commerce'], $info['Recherche'], $info['Combat'],
            $info['Construction'], $info['Navigation'], $info['Race'],
            $info['Titre'], $info['GameGrade'], $info['pts_architecte'], $info['pts_mineur'],
            $info['pts_science'], $info['pts_commercant'], $info['pts_amiral'],
            $info['pts_guerrier'], $_SESSION['_login']
            )
        );
    }

}
if(isset($_POST["JOUEUR"])) {

    foreach ($_POST as $k => $v) $_POST[$k] = gpc_esc($v);

    $_POST['Commerce'] = intval($_POST['Commerce']);
    $_POST['Recherche'] = intval($_POST['Recherche']);
    $_POST['Combat'] = intval($_POST['Combat']);
    $_POST['Construction'] = intval($_POST['Construction']);
    $_POST['Economie'] = intval($_POST['Economie']);
    $_POST['Navigation'] = intval($_POST['Navigation']);

    $_POST['Points'] = DataEngine::strip_number($_POST['Points']);
    $_POST['pts_architecte'] = DataEngine::strip_number($_POST['pts_architecte']);
    $_POST['pts_mineur'] = DataEngine::strip_number($_POST['pts_mineur']);
    $_POST['pts_science'] = DataEngine::strip_number($_POST['pts_science']);
    $_POST['pts_commercant'] = DataEngine::strip_number($_POST['pts_commercant']);
    $_POST['pts_amiral'] = DataEngine::strip_number($_POST['pts_amiral']);
    $_POST['pts_guerrier'] = DataEngine::strip_number($_POST['pts_guerrier']);

    foreach ($_POST as $k => $v) $_POST[$k] = sqlesc($v, true);

    $query = <<<q
        UPDATE SQL_PREFIX_Membres SET POINTS='%d', ship='%s',
        Economie='%d', Commerce='%d', Recherche='%d', Combat='%d',
        Construction='%s', Navigation='%d',
        pts_architecte='%d', pts_mineur='%d',
        pts_science='%d', pts_commercant='%d', pts_amiral='%d',
        pts_guerrier='%d', Date=now() WHERE Joueur='%s'
q;
    DataEngine::sql(sprintf($query, $_POST['Points'], $_POST['ship'],
        $_POST['Economie'], $_POST['Commerce'], $_POST['Recherche'], $_POST['Combat'],
        $_POST['Construction'], $_POST['Navigation'],
        $_POST['pts_architecte'], $_POST['pts_mineur'],
        $_POST['pts_science'], $_POST['pts_commercant'], $_POST['pts_amiral'],
        $_POST['pts_guerrier'], $_SESSION['_login']
        )
    );

}

if (isset($_POST['pwd']) && !($_SESSION['_login'] == 'test' && DE_DEMO)) {
    $query = "UPDATE SQL_PREFIX_Users SET Password='".md5(sqlesc($_POST['pwd']))."' WHERE Login='".$_SESSION['_login']."'";
    DataEngine::sql($query);
}

$mysql_result = DataEngine::sql("Select m.*,g.Grade from SQL_PREFIX_Membres as m, SQL_PREFIX_Grade as g WHERE Joueur='".$_SESSION['_login']."' AND (m.Grade=g.GradeId)");
$ligne = mysql_fetch_assoc($mysql_result);

//$tabrace[0]='Cyborg';
//$tabrace[1]='Humain';
//$tabrace[2]='Jamazoide';
//$tabrace[3]='Magumar';
//$tabrace[4]='Mosoran';
//$tabrace[5]='Ozoidien';
//$tabrace[6]='Plentropien';
//$tabrace[7]='Weganien';
//$tabrace[8]='Zuup';

$shiplist[]='Sonde';
$shiplist[]='Navette';
$shiplist[]='Chasseur';
$shiplist[]='Corvette';
$shiplist[]='Frégate';
$shiplist[]='Cargo';
$shiplist[]='Croiseur';
$shiplist[]='Intercepteur';
$shiplist[]='Croiseur interstellaire';
$shiplist[]='Sentinelle';
$shiplist[]='Vaisseau de guerre';
$shiplist[]='Centaure';
$shiplist[]='Minotaure';
$shiplist[]='Transporteur';
$shiplist[]='Cerbère';
$shiplist[]='Kraken';
$shiplist[]='Hadès';
$shiplist[]='Léviathan';
$shiplist[]='Transporteur intergalactique';
$shiplist[]='Station de guerre';
$shiplist[]='SG Armaggedon';


require_once(TEMPLATE_PATH.'mafiche.tpl.php');

$tpl = tpl_mafiche::getinstance();
$tpl->page_title = 'EU2: Ma fiche';

$tpl->header($ligne);
$tpl->add_row('Points','Points',$ligne['Points']);
$tpl->add_row('Points architecte','pts_architecte',$ligne['pts_architecte']);
$tpl->add_row('Points mineur','pts_mineur',$ligne['pts_mineur']);
$tpl->add_row('Points science','pts_science',$ligne['pts_science']);
$tpl->add_row('Points commerçant','pts_commercant',$ligne['pts_commercant']);
$tpl->add_row('Points amiral','pts_amiral',$ligne['pts_amiral']);
$tpl->add_row('Points guerrier','pts_guerrier',$ligne['pts_guerrier']);


$tpl->add_row('Commerce','Commerce',$ligne['Commerce']);
$tpl->add_row('Recherche','Recherche',$ligne['Recherche']);
$tpl->add_row('Combat','Combat',$ligne['Combat']);
$tpl->add_row('Construction','Construction',$ligne['Construction']);
$tpl->add_row('Économie','Economie',$ligne['Economie']);
$tpl->add_row('Navigation','Navigation',$ligne['Navigation']);

$tpl->add_row('Race','',$ligne['Race']);
//$tpl->add_row_select('Race', '', $tabrace, $ligne['Race']);
$tpl->add_row_select('Dernier chassis disponible', 'ship', $shiplist, $ligne['ship']);

$tpl->add_row('Titre','',$ligne['Titre']);
$tpl->add_row('Grade','',$ligne['GameGrade']);
$tpl->add_row('Grade dans l\'alliance','',$ligne['Grade']);

$tpl->footer();
$tpl->doOutput();

