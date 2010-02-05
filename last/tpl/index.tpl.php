<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
**/
if (!SCRIPT_IN) die('Need by included');
/*
$tpl = new tpl_index();
// Partie insertion
$tpl->insert_part1();
$tpl->SelectOption($cctype, 'Type', $_POST["Type"]);
$tpl->insert_part2(DataEngine::a_ressources());
$tpl->RessTextRow(DataEngine::a_ressources(), 'RESSOURCE');
$tpl->insert_part3($erreur, $info);
// Partie de recherche
$tpl->search_part1($inactif);
$tpl->SelectOption($cctype, '', $Rech['RechercheType']);
$tpl->search_part2($Rech);
$tpl->RessImgRow(DataEngine::a_ressources());
$tpl->search_part3(DataEngine::a_ressources(),$Rech);

// Partie de résultats
$tpl->result_part1($PageCurr, $MaxPage, $myget);
$tpl->search_row($ligne,$disabled,$cctype,$myget);
$tpl->result_pagination($PageCurr, $MaxPage,$myget);
$tpl->DoOutput();
*/
class tpl_index extends output {
	protected $BASE_FILE = '';

	public
		function __construct() {
		$this->BASE_FILE = ROOT_URL."index.php";

		parent::__construct();
	}

	public 
		function insert_part1() {
		$bulle1 = bulle("Coller ici les détails d'une planète, joueur ou d'un vortex<br/>(Ctrl+A puis Ctrl+C après avoir ouvert une fiche)");
$out =<<<PART1
		<form align='center' name='data' method='post' action='{$this->BASE_FILE}'>
		<TABLE id='Ttableau' align=center>
		<TR id='titreTRtableau'>		
		<TD id='titreTDtableau' align='center' colspan=8>Ajout des corps célestes</TD>
		</TR><input name='phpparser' type='hidden' value='0'>
		<TR id='TRtableau'>						
		<TD $bulle1 align='center' colspan=6 id='TDtableau'>
		<TEXTAREA id="INTableau" cols="50" rows="4" name='importation'></TEXTAREA>
		</TD>
		<TD  align='center' id='TDtableau'>
		<input onclick='interpreter(document.getElementsByName("importation")[0].value, true); GestionFormulaire("Type");' type='button' id='INBTtableau' value='Automatique'>
		<br/><br/>
		<input onclick='interpreter(document.getElementsByName("importation")[0].value, false); GestionFormulaire("Type");' type='button' id='INBTtableau' value='Manuel'>
		</TD>
		</TR>	
		<TR id='teteTRtableau'>
		<TD id='teteTDtableau'>Type</TD>
		<TD id='teteTDtableau'>Coordonnée Entrée</TD>
		<TD id='teteTDtableau'>Coordonnée Sortie</TD>		
		<TD id='teteTDtableau'>Nom</TD>
		<TD id='teteTDtableau'>Empire</TD>
		<TD id='teteTDtableau'>Infos</TD>
		<TD id='teteTDtableau'>&nbsp;</TD>
		</TR>			
		<TR id='teteTRtableau'>						
		<TD id='TDtableau'>
		<select onchange='GestionFormulaire("Type");' id='INtableau' name='Type'>
PART1;
		$this->PushOutput($out);
	}
	public 
		function insert_part2($Ressource) {
		$bulle1 = bulle("Coordonnée pour l&#39;information<br>au format ID : xxxx-xx-xx-xx ou xxxx");
		$bulle2 = bulle("Coordonnée de sortie de Vortex uniquement<br>au format ID : xxxx-xx-xx-xx ou xxxx");
		$bulle3 = bulle("Nom, du joueur ou de la planète");
		$bulle4 = bulle("Empire d&#39;appartenance du joueur");
		$bulle5 = bulle("Nom de planète<br/>Données diverses");
		$bulle6 = bulle("Valider la saisie");
$out =<<<PART2
			</select>
		</TD>		
	 	<TD id='TDtableau'><input maxlength=16 id='INtableau110' type='text' name='COORIN'  value='' $bulle1/></TD>
	 	<TD id='TDtableau'><input maxlength=16 id='INtableau110' type='text' name='COOROUT' value='' $bulle2/></TD>
	 	<TD id='TDtableau'><input maxlength=30 id='INtableau120' type='text' name='USER'    value='' $bulle3/></TD>
	 	<TD id='TDtableau'><input maxlength=100 id='INtableau120' type='text' name='EMPIRE'  value='' $bulle4/></TD>
	 	<TD id='TDtableau'><input maxlength=100 id='INtableau200' type='text' name='INFOS'   value='' $bulle5/></TD>
	 	<TD id='TDtableau'><input id='INBTtableau' type='submit' value='Insérer' $bulle6/></TD>
	 	</TR>	 		 		 		 	
	 	</TABLE>

	 	<!--Information sur la planete a inserer-->
	 	<TABLE name='AddTabRessource' style="visibility:hidden; position:absolute" id='Ttableau' align=center><TD id='teteTDtableau'>
		<TR id='titreTRtableau'>		
		<TD id='titreTDtableau' align='center' colspan=10>Informations détaillées planète/Astéroïde</TD>
		</TR>
		<TR id='teteTRtableau'>
PART2;
		$this->PushOutput($out);
		$this->RessImgRow($Ressource);
		$this->PushOutput('	</TR>');
	}

	public 
		function insert_part3($erreur, $info) {
		$erreur = ($erreur != "") ? "<font color=#FF0000><center>$erreur</center></font>": "";
		$info   = ($info   != "") ? "<font color=green><center>$info</center></font>": "";
$out =<<<PART3
	 	<SCRIPT type='text/javascript'>
		GestionFormulaire("Type");
		</SCRIPT>
	 	{$erreur}{$info}
	   	</form>
PART3;
		$this->PushOutput($out);
	}

	public 
		function search_part1($inactif="-1") {
		$status["-1"] = ($inactif=="-1") ? " selected" : "";
		$status[0]  = ($inactif==0)  ? " selected" : "";
		$status[1]  = ($inactif==1)  ? " selected" : "";
//		$bulle1 = bulle('Date au format: AAAA-MM-JJ (ex: 2009-12-25)');
$out =<<<PART1
		<form align='center' name='rechercher' method='post' action='{$this->BASE_FILE}'>
		<TABLE id='Ttableau' align=center><TR id='teteTDtableauTRtableau' align=center>
		<TR id='titreTRtableau'>		
		<TD id='titreTDtableau' align='center' colspan=11>Recherche des corps célestes</TD>
		</TR>
		<TR id='TRtableau'>
		<TD id='teteTDtableau'>Statut</TD>
		<!--<td id='teteTDtableau'>Date</td>-->
		<td id='teteTDtableau'>Type</td>
		<td id='teteTDtableau'>SS</td>
		<td id='teteTDtableau'>Rayon</td>
		<td id='teteTDtableau'>Joueur</td>
		<td id='teteTDtableau'>Empire</td>
		<td id='teteTDtableau'>Infos</td>
		<td id='teteTDtableau'>Note</td>
		<td id='teteTDtableau'>Moi</td>
		<td id='teteTDtableau'>&nbsp;</td>
		</TR>
		<TR id='TRtableau'>
		<TD id='TDtableau'>
			<select id='INtableau' name='RechercheStatut'>
				<option value='-1'{$status["-1"]}>&nbsp;</option>
				<option value=0{$status[0]}>Actif</option>
				<option value=1{$status[1]}>Inactif</option>
			</select>
		</TD>
		<!--<TD id='TDtableau' {$bulle1}><INPUT id='INtableau80' maxlength=10 TYPE='text' name='RechercheDate' value=''/></TD>-->
		<TD id='TDtableau'>
		<select onchange='RechercheOnType();' id='INtableau' name='RechercheType'>
		<option value='-1'>&nbsp;</option>
PART1;
		$this->PushOutput($out);
	}
	public 
		function search_part2($Rech) {
		$Rech["RechercheEmpire"] = htmlentities(stripslashes($Rech["RechercheEmpire"]), ENT_QUOTES, 'utf-8');
		$Rech["RechercheInfos"]  = htmlentities(stripslashes($Rech["RechercheInfos"]),  ENT_QUOTES, 'utf-8');
		$Rech["RechercheNote"]   = htmlentities(stripslashes($Rech["RechercheNote"]),   ENT_QUOTES, 'utf-8');
		$checkedmoi = $Rech['RechercheMoi'] == '1' ? " checked" : "";
		$extended = (in_array($Rech['RechercheType'], array(2,4))) ? "visibility: visible;position:;": "visibility: hidden;position:absolute";
$out =<<<PART2
			</select>
		</TD>
		<TD id='TDtableau'><INPUT id='INtableau50' maxlength=5 TYPE='text' name='RecherchePos' value='{$Rech['RecherchePos']}'></INPUT></TD>
		<TD id='TDtableau'><INPUT id='INtableau50' maxlength=3 TYPE='text' name='RechercheRayon' value='{$Rech['RechercheRayon']}'></INPUT></TD>
		<TD id='TDtableau'><INPUT id='INtableau80' TYPE='text' name='RechercheUser' value='{$Rech['RechercheUser']}'></INPUT></TD>
		<TD id='TDtableau'><INPUT id='INtableau110' maxlength=100 TYPE='text' name='RechercheEmpire' value="{$Rech['RechercheEmpire']}"></INPUT></TD>
		<TD id='TDtableau'><INPUT id='INtableau110' maxlength=100 TYPE='text' name='RechercheInfos' value="{$Rech['RechercheInfos']}"></INPUT></TD>
		<TD id='TDtableau'><INPUT id='INtableau200' maxlength=100 TYPE='text' name='RechercheNote' value="{$Rech['RechercheNote']}"></INPUT></TD>
		<TD id='TDtableau'><INPUT TYPE='checkbox'name='RechercheMoi' value='1'{$checkedmoi}/></TD>
		<TD id='TDtableau' colspan=10><INPUT id='INBTtableau' TYPE='submit' value='Rechercher'/><INPUT id='INBTtableau' Onclick="location.href='{$this->BASE_FILE}?ResetSearch=Yes'" TYPE='button' value='Afficher tout'/></TD>		
		</TR></TABLE>
	 	<TABLE id='Ttableau' align=center name='RechercheTabRessource' style='$extended'><TD id='teteTDtableau'>		
		<TR id='teteTRtableau'>
PART2;
		$this->PushOutput($out);
	}

	public 
		function search_part3($array,$Rech=null) {
		$out = "\t\t</TR>\n\t\t<TR id='TRtableau'>\n";

		foreach($array as $id => $Ress) {
			$selected[0]   = ($Rech['RechercheRessource'.$id]=='-1'   ? " selected" : "");
			$selected[10]  = ($Rech['RechercheRessource'.$id]=='>=10' ? " selected" : "");
			$selected[20]  = ($Rech['RechercheRessource'.$id]=='>=20' ? " selected" : "");
			$selected[30]  = ($Rech['RechercheRessource'.$id]=='>=30' ? " selected" : "");
			$selected[40]  = ($Rech['RechercheRessource'.$id]=='>=40' ? " selected" : "");
			$selected[50]  = ($Rech['RechercheRessource'.$id]=='>=50' ? " selected" : "");
			$selected[60]  = ($Rech['RechercheRessource'.$id]=='>=60' ? " selected" : "");
			$selected[70]  = ($Rech['RechercheRessource'.$id]=='>=70' ? " selected" : "");
			$selected[80]  = ($Rech['RechercheRessource'.$id]=='>=80' ? " selected" : "");
			$selected[90]  = ($Rech['RechercheRessource'.$id]=='>=90' ? " selected" : "");
			$selected[100] = ($Rech['RechercheRessource'.$id]=='=100' ? " selected" : "");
$out .=<<<PART3
			<TD id='teteTDtableau'>			
			<select id='INtableau' name='RechercheRessource$id'>
					<option value=''{$selected[0]}>&nbsp;</option>
					<option value='>=10'{$selected[10]}>&gt;=10%</option>
					<option value='>=20'{$selected[20]}>&gt;=20%(peu)</option>
					<option value='>=30'{$selected[30]}>&gt;=30%</option>
					<option value='>=40'{$selected[40]}>&gt;=40%(nor)</option>
					<option value='>=50'{$selected[50]}>&gt;=50%</option>
					<option value='>=60'{$selected[60]}>&gt;=60%</option>
					<option value='>=70'{$selected[70]}>&gt;=70%(bcp)</option>
					<option value='>=80'{$selected[80]}>&gt;=80%</option>
					<option value='>=90'{$selected[90]}>&gt;=90%</option>
					<option value='=100'{$selected[100]}>=100%</option>
				</select>
			</TD>
PART3;
		}
		$out .= "\t\t</TR></table>\n\t\t</form>\n";
		$this->PushOutput($out);
// 		$this->debug();
	}

	public 
		function result_pagination($PageCurr,$MaxPage,$myget) {
		$pg_0					= AlterGet($myget,"Page",0);
		$pg_prec			= AlterGet($myget,"Page",($PageCurr-1));
		$pg_next			= AlterGet($myget,"Page",($PageCurr+1));
		$pg_end				= AlterGet($myget,"Page",$MaxPage);
		$btn_prev			= ($PageCurr > 0) ? "<img onclick=\"location.href='{$this->BASE_FILE}{$pg_prec}';\" width=16 height=16 src=\"./Images/Btn-Precedent.png\"></img>":'';
		$btn_next			= ($PageCurr < $MaxPage) ? "<img onclick=\"location.href='{$this->BASE_FILE}{$pg_next}';\" width=16 height=16 src=\"./Images/Btn-Suivant.png\"></img>":'';
		$real_pg			= $PageCurr+1;
		$real_max			= $MaxPage+1;
// style="color:#FC944E;" bgcolor="#000000"
$out =<<<PAGINATION
	<TR>
		<TD id='pages_tableau' align=right colspan="11">
			<img onclick="location.href='{$this->BASE_FILE}{$pg_0}';" width=16 height=16 src="./Images/Btn-Debut.png"></img>
			{$btn_prev} &nbsp;{$real_pg} / {$real_max}&nbsp; {$btn_next}
			<img onclick="location.href='{$this->BASE_FILE}{$pg_end}';" width=16 height=16 src="./Images/Btn-Fin.png"></img>
		</TD>
	</TR>
PAGINATION;
		$this->PushOutput($out);
	}
	public 
		function result_part1($PageCurr,$MaxPage,$myget) {
		$pg_0					= AlterGet($myget,"Page",0);
		$pg_prec			= AlterGet($myget,"Page",($PageCurr-1));
		$pg_next			= AlterGet($myget,"Page",($PageCurr+1));
		$pg_end				= AlterGet($myget,"Page",$MaxPage);
		$Tri_Date			= AlterGet($myget,"TriDate",($_GET["TriDate"]==1 ? 0 : 1));
		$Tri_Type			= AlterGet($myget,"TriType",($_GET["TriType"]==1 ? 0 : 1));
		$Tri_Coor			= AlterGet($myget,"TriCoor",($_GET["TriCoor"]==1 ? 0 : 1));
		$Tri_CoorOut	= AlterGet($myget,"TriCoorOut",($_GET["TriCoorOut"]==1 ? 0 : 1));
		$Tri_Joueur		= AlterGet($myget,"TriJoueur",($_GET["TriJoueur"]==1 ? 0 : 1));
		$Tri_Emp			= AlterGet($myget,"TriEmp",($_GET["TriEmp"]==1 ? 0 : 1));
		$Tri_Infos		= AlterGet($myget,"TriInfos",($_GET["TriInfos"]==1 ? 0 : 1));
		$Tri_Note			= AlterGet($myget,"TriNote",($_GET["TriNoter"]==1 ? 0 : 1));
		$col_user			= ($_SESSION["_Perm"]>=AXX_ADMIN)	? '<TD id="TeteTDtableau">User</TD>': '';

// 		$this->PushOutput('	<TABLE border=1 width=90% bgcolor=#330033 cellpadding=2 cellspacing=3 align=center>');
		$this->PushOutput('	<TABLE width=90% cellpadding=2 cellspacing=3 align=center>');
		$this->result_pagination($PageCurr, $MaxPage,$myget);
$out =<<<PART1
	<TR id="TeteTRtableau">		
		<TD id="TeteTDtableau">Statut</TD>
		<TD id="TeteTDtableau" onclick="location.href='{$this->BASE_FILE}{$Tri_Date}';">Date</TD>
		<TD id="TeteTDtableau" onclick="location.href='{$this->BASE_FILE}{$Tri_Type}';">Type</TD>
		<TD id="TeteTDtableau" onclick="location.href='{$this->BASE_FILE}{$Tri_Coor}';">Coordonnée In</TD>
		<TD id="TeteTDtableau" onclick="location.href='{$this->BASE_FILE}{$Tri_CoorOut}';">Coordonné Out</TD>
		<TD id="TeteTDtableau" onclick="location.href='{$this->BASE_FILE}{$Tri_Joueur}';">Joueur</TD>
		<TD id="TeteTDtableau" onclick="location.href='{$this->BASE_FILE}{$Tri_Emp}';">Empire</TD>
		<TD id="TeteTDtableau" onclick="location.href='{$this->BASE_FILE}{$Tri_Infos}';">Infos</TD>
		<TD id="TeteTDtableau" onclick="location.href='{$this->BASE_FILE}{$Tri_Note}';">Note</TD>
		<TD id="TeteTDtableau">&nbsp;</TD>		
		$col_user
	</TR>
PART1;
		$this->PushOutput($out);
	}

	public 
		function search_row($ligne,$disabled,$cctype,$myget) {
		$IDcurr = $ligne["ID"];
		$s_inactif = $ligne["INACTIF"] ? "Activer" : "Désactiver";
		$ligne["INACTIF"] = (($ligne["INACTIF"]+1)%2);
		$coorin = bulle("Coordonnés<br>au format xxxx-xx-xx-xx");
$out =<<<ROW
	<TR valign=center id='TRtableau'>
		<TD valign=center align=center id='TDtableau'>
			<Form name='ModifStatut' method='post' action='{$this->BASE_FILE}$myget'>
			<input type='hidden' name='ID' value='{$ligne["ID"]}'>
			<input type='hidden' name='NewStatut' value='{$ligne["INACTIF"]}'>
			<input id='INBTtableau' type='submit' value='{$s_inactif}'></form>
		</TD>
	<form name='Modifier' method='post' action='{$this->BASE_FILE}$myget'><input type='hidden' name='ID' value='{$ligne["ID"]}'>
	<TD id='TDtableau'>{$ligne["DATE"]}</TD>
	<TD id='TDtableau'>
		<input type='hidden' id='modifDELETE{$ligne["ID"]}' name='modifDELETE' value='0'>
		<select {$disabled}id='INtableau' name='modifTYPE'>
ROW;
		$this->PushOutput($out);
		$this->SelectOptions($cctype,$ligne["TYPE"]);

		$ligne["EMPIRE"] = htmlentities($ligne["EMPIRE"], ENT_QUOTES, 'utf-8');
		$ligne["INFOS"] = htmlentities($ligne["INFOS"], ENT_QUOTES, 'utf-8');
		$ligne["NOTE"] = htmlentities($ligne["NOTE"], ENT_QUOTES, 'utf-8');
$out =<<<ROW
	</TD>
	<TD align=center id='TDtableau'>
		{$ligne["POSIN"]}-{$ligne["COORDET"]}<input type=hidden name=modifCOORIN value='{$ligne["POSIN"]}-{$ligne["COORDET"]}'/>
	</TD>
	<TD align=center id='TDtableau'>
		{$ligne["POSOUT"]}-{$ligne["COORDETOUT"]}<input type=hidden name=modifCOOROUT value='{$ligne["POSOUT"]}-{$ligne["COORDETOUT"]}'/>
	</TD>
	<TD id='TDtableau'><input {$disabled}style='width:120px;' id='INTableau' type='text' name='modifUSER' value='{$ligne["USER"]}'/></TD>
	<TD id='TDtableau'><input {$disabled}style='width:120px;' id='INTableau' type='text' name='modifEMPIRE' value="{$ligne["EMPIRE"]}"/></TD>
	<TD id='TDtableau'><input {$disabled}style='width:180px;' id='INTableau' type='text' name='modifINFOS' value="{$ligne["INFOS"]}"/></TD>
	<TD id='TDtableau'><input {$disabled}style='width:200px;' id='INtableau' type='text' name='NOTE' value="{$ligne["NOTE"]}"/></TD>
ROW;
	if( ($_SESSION['_Perm']>=AXX_MODO) || (strtolower($ligne["UTILISATEUR"])==strtolower($_SESSION['_login']))) {
$out .=<<<ROW2
	<TD align=center id='TDtableau'><input  id='INBTtableau' type='submit' value='Modifier'>
		<input onclick='if(confirm("Etes-vous sûr de vouloir supprimer cette entrée?")) {document.getElementById("modifDELETE{$ligne["ID"]}").value=1; return true;} else return false;' id='INBTtableau' type='submit' value='Supprimer'>
	</TD>
	<TD id='TDtableau'>{$ligne["UTILISATEUR"]}</TD>
ROW2;
		} else {
			$out.="<TD align=center id='TDtableau'>&nbsp;</TD>\n<TD align=center id='TDtableau'>&nbsp;</TD>\n";
		}

	$out.="\t\t</TD>\n\t</TR>";

	//Gestion des data planètes
	if (in_array($ligne["TYPE"], array(2,4))) {
$out .=<<<ROWPLANET1
	<TR>
	<TD id='TDtableau' height=14 colspan="11">
	<TABLE height=14 width=90% align=left>
	<TR height=14>
ROWPLANET1;
	$resscons = array('','très peu','peu','###','normal','###','moyennement','beaucoup','considérablement','énormément');
	foreach(DataEngine::a_ressources() as $id => $Ress) {
		$value=$ligne[$Ress["Field"]];
		if (strpos($value,'%') !== false) $nbbarre = floor(substr($value,0,-1)/10);
		elseif (in_array($value,$resscons)) $nbbarre = array_search($value,$resscons);
		else $nbbarre = min(floor(max(4000,$value)/4000),10);

		if($nbbarre>=7) $color="#008000";
		else if($nbbarre>=4) $color="#FF8040";
		else $color="#FF0000";
		$barre= str_pad("",$nbbarre*7,"&nbsp; ");
$out .=<<<ROWPLANET2
		<TD     onmouseover="document.getElementsByName('{$ligne["ID"]}ModifRessource$id')[0].style.visibility='visible';
									document.getElementsByName('{$ligne["ID"]}BarreRessource$id')[0].style.position='absolute';
									document.getElementsByName('{$ligne["ID"]}BarreRessource$id')[0].style.visibility='hidden';
									document.getElementsByName('{$ligne["ID"]}ModifRessource$id')[0].style.width=80;"\r\n
			onmouseout="document.getElementsByName('{$ligne["ID"]}ModifRessource$id')[0].style.visibility='hidden';
									document.getElementsByName('{$ligne["ID"]}BarreRessource$id')[0].style.position='';
									document.getElementsByName('{$ligne["ID"]}BarreRessource$id')[0].style.visibility='visible';
									document.getElementsByName('{$ligne["ID"]}ModifRessource$id')[0].style.width=0;"\r\n
		valign=center width=10% id='TDtableau'>
		<IMG width=12 height=12 src='{$Ress['Image']}'>
		&nbsp;<span name='{$ligne["ID"]}BarreRessource{$id}' valign=center style='font-size:10; background-color:{$color}'>{$barre}</span>
		<input {$disabled}style='visibility:hidden; width:0px' type='text' id='INtableau' name='{$ligne["ID"]}ModifRessource{$id}' value='{$value}'>
		</TD>
ROWPLANET2;
		}
$out .=<<<ROWPLANET3
	</TR>
	</TABLE>
	</TD>
	</TR>
ROWPLANET3;
	} // if type = 2,4
		$out .="</form>";

		$this->PushOutput($out);
	}

// 	public 
// 		function SelectOption($array,$key='',$idselected=-1) {
// 		$out='';
// 		foreach ($array as $id => $v) {
// 			$selected = ($idselected==$id ? " selected" : "");
// $out .=<<<SELECT
// 				<option name="$key$id" value="$id"$selected>$v</option>
// 
// SELECT;
// 		}
// 		$this->PushOutput($out);
// 	}

	public 
		function RessTextRow($array,$key='') {
		$out='';
		foreach ($array as $id => $v) {
			$selected = ($idselected==$id ? " selected" : "");
			$value = ($_POST["RESSOURCE$id"] != "") ? $_POST["RESSOURCE$id"]:'';
$out .=<<<TEXT
			<TD id='teteTDtableau'>
			<INPUT style='width:60;' id='INtableau' type='text' name='$key$id' value='$value'/>
			</TD>
TEXT;
		}
		$this->PushOutput($out);
	}

	public 
		function RessImgRow($array) {
		$out='';
		foreach ($array as $id => $v) {
$out .=<<<IMG
			<TD id='TDtableau'>
			<IMG width=15 height=15 src='{$v['Image']}'>
			</TD>
IMG;
		}
		$this->PushOutput($out);
	}

	public 
		function DoOutput($include_menu=true, $include_header=true) {
		$this->PushOutput("</TABLE>\n</BODY></HTML>");
		parent::DoOutput(); // false false ? header menu
	}

/**
 *
 * @return tpl_index
 */
	static public
		function getinstance() {
		if ( ! DataEngine::_tpl_defined(get_class()) )
			DataEngine::_set_tpl(get_class(),new self());

			return DataEngine::tpl(get_class());
	}
}