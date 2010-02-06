<?php
/**
 * @author Alex10336
 * Dernière modification: $Id$
 * @license GNU Public License 3.0 ( http://www.gnu.org/licenses/gpl-3.0.txt )
 * @license Creative Commons 3.0 BY-SA ( http://creativecommons.org/licenses/by-sa/3.0/deed.fr )
 *
**/
if (!SCRIPT_IN) die('Need by included');

class tpl_eadmin extends output {
	protected $BASE_FILE = '';

	private $idcols=0;
	private $cols_cls=array('#d6d6d6','#CCCCCC','#AAAAAA');

	public function __construct() {
		$this->BASE_FILE = ROOT_URL."EAdmin.php";

		parent::__construct();

		$this->PushOutput("<table><tr>\n");
	}

	public function log_header() {
$out =<<<col1_h
<td valign="top">
	<TABLE bgcolor='#AAAAAA'>
	<TR>
		<TD>Date</TD>
		<TD>Message</TD>
		<TD>IP</TD>
	</TR>

col1_h;
		$this->PushOutput($out);
	}

	public function log_row($ligne) {
		$this->idcols++;
		$cls = $this->cols_cls[($this->idcols%2)];
$out =<<<col1_r
	<TR bgcolor="$cls">
		<TD>{$ligne["DATE"]}</TD>
		<TD>{$ligne["LOGIN"]}</TD>
		<TD>{$ligne["IP"]}</TD>
	</TR>

col1_r;
		$this->PushOutput($out);
	}

	public function log_footer() {
		$this->PushOutput("</table></td>\n");
	}

	public function admin_header($version) {
		$helpmsg = <<<EOF
<b>Niveau de permission :</b>
<br/><b>Invité</b>: Aucun droit, attente d'activation d'un <i>Super-Administrateur</i>.
<br/><b>Membre</b>: Juste rajouter des éléments
<br/><b>Modérateur</b>: Peut modifier toutes les lignes du tableau
<br/><b>Administrateur</b>: Peut rajouter et gérer les membres, organiser la hierarchie + supprimer n'importe quelle ligne du tableau
<br/><b>Super-Administrateur</b>: Visiblement, vous l'êtes !
EOF;
		$helpmsg = bulle($helpmsg);

$out =<<<col2_h
<TD valign='top'>
<TABLE bgcolor='#D6D6D6'>
	<TR>
		<TD colspan=3><img $helpmsg src='./Images/help.png'>&nbsp;&nbsp;<b>E</b>mpire <b>U</b>niverse 2: <b>D</b>ata <b>E</b>ngine ({$this->version})</TD>
	</TR><TR>
		<TD>&nbsp</td><TD colspan=2><a href='http://app216.free.fr/eu2/tracker/' target='_blank'>Un bug, une suggestion ?</a></TD>
	</TR><TR>
		<TD align=right><b>Mysql <br/>PHP <br/>GD </b></td><TD colspan=2>{$version[0]}<br/>{$version[1]}<br/>{$version[2]}</TD>
	</TR><TR>
		<TD bgcolor="#AAAAAA" colspan=2>Gestion des droits utilisateur: </td>
                <TD bgcolor="#cccccc" colspan="2"><a href='%ROOT_URL%perms.php'>Cliquez ici</a></TD>
	</TR>

col2_h;
		$this->PushOutput($out);
	}
	public function admin_vortex($dates,$cleanvortex,$cron_conf) {

            if ($cron_conf['enabled']) {
                $lastrun = date("Y-m-d H:i:s", $cron_conf['lastrun']);
                $cron =<<<c
		<TR bgcolor="#d6d6d6">
			<TD>Mode auto actif (<a href="%ROOT_URL%EAdmin.php?switch=vortex_cron">désactiver</a>)</TD>
			<TD colspan="2">Dernière fois le <font color=green>{$lastrun}</font></TD>
		</TR>
c;
            } else {
                $cron =<<<c
		<TR bgcolor="#d6d6d6">
			<TD colspan="3">Mode auto inactif (<a href="%ROOT_URL%EAdmin.php?switch=vortex_cron">Activer ?</a>)</TD>
		</TR>
c;
            }
$out =<<<col2_vortex
		<form name=cleanvortex method='post' action='{$this->BASE_FILE}'>
		<TR bgcolor="#AAAAAA">
			<TD colspan=3>Nettoyage des vortex:</TD>
			<TD rowspan=4>
				<input type="hidden" name="cleanvortex" value="{$dates[1]}"><input type="hidden" name="cleanvortex_inactif" value="{$dates[2]}"><input type="submit" value="Nettoyer\nmaintenant">
			</TD>
		</TR>
		<TR bgcolor="#CCCCCC">
			<TD>Temps serveur:</TD>
			<TD colspan=2><font color=red>{$dates[0]}</font></TD>
		</TR>
                {$cron}
		<TR bgcolor="#CCCCCC">
			<TD colspan=3>Les Vortex plus anciens que "{$dates[1]}" seront inactivés.<br/>Les Vortex inactifs (depuis {$dates[2]}) seront supprimés.</TD>
		</TR>

col2_vortex;
		if (is_array($cleanvortex))
$out .=<<<col2_cleanvortex
		<TR bgcolor="#CCCCCC">
			<TD colspan=4>{$cleanvortex[0]} vortex supprimé(s), et {$cleanvortex[1]} désactivé(s).</TD>
		</TR>

col2_cleanvortex;
		$this->PushOutput($out);
		$this->PushOutput("</form>\n");
	}

	public function admin_user_header() {
		$this->idcols=0;
$out = <<<col2_h
	<TR bgcolor="{$this->cols_cls[2]}">
		<TD colspan=3>Liste des membres</TD>
		<TD rowspan=2 bgcolor="{$this->cols_cls[2]}">&nbsp</TD>
	</TR>
	<TR>
		<TD>Login</TD><TD>Pwd</TD><TD>Permission</TD>
	</TR>

col2_h;
		$this->PushOutput($out);
	}

	public function admin_user_row($ligne) {
		$this->idcols++;
		$cls= $this->cols_cls[($this->idcols%2)];
$out = <<<col2_ur
	<form name=modifuser method='post' action='{$this->BASE_FILE}'>
	<TR bgcolor="$cls">
		<TD>{$ligne["Login"]}</TD>
		<input name='log' type='hidden' value='{$ligne["Login"]}'>
		<input name='oldpass' type='hidden' value='{$ligne["Password"]}'>
		<TD><input name='pwd' type='text' value='{$ligne["Password"]}'></TD>
		<td><select name='perm'>

col2_ur;
		$this->PushOutput($out);
		$this->SelectOptions(DataEngine::s_perms(), $ligne["Permission"]);
$out = <<<col2_ur2
	</select></td>
		<TD bgcolor="{$this->cols_cls[2]}"><input type='submit' value='Modifier'></TD>
	</TR>
	</form>

col2_ur2;
		$this->PushOutput($out);
	}

	public function cleaning_header($numrow=0) {
		$this->idcols=0;
		$numrow++;
$out =<<<cleaning_header
		<form name="cleaning" method='post' action='{$this->BASE_FILE}'>
		<TR bgcolor="#AAAAAA">
			<TD colspan=2>Nettoyage divers...</TD>
			<TD>Plus anciens que</TD>
			<TD rowspan={$numrow} bgcolor="#AAAAAA">
				<input type="submit" value="Nettoyer">
			</TD>
		</TR>
cleaning_header;

		$this->PushOutput($out);

	}

	public function cleaning_row($key,$title,$select) {
		$this->idcols++;
		$cls= $this->cols_cls[($this->idcols%2)];

$out =<<<cleaning_row
		<TR bgcolor="{$cls}">
			<TD colspan=2>{$title}</TD>
			<td><select name="{$key}">
cleaning_row;
		$this->PushOutput($out);

		$this->SelectOptions($select,-1,'',true);

$out =<<<cleaning_row
			</select></td>
		</TR>
cleaning_row;

		$this->PushOutput($out);
	}

	public function cleaning_footer() {

$out = <<<cleaning_footer
			</select></td>
		</TR>
		</form>
cleaning_footer;

		$this->PushOutput($out);
	}

	public function cleaning_msg($array) {
		$msg='';
		foreach($array as $tp => $num)
			$msg[] = "$num $tp supprimé";
		$msg = implode('<br/>',$msg);
$out .=<<<cleaning_msg
		<TR bgcolor="#CCCCCC">
			<TD colspan=4>{$msg}</TD>
		</TR>

cleaning_msg;

		$this->PushOutput($out);
	}

	public function admin_footer() {
		$this->PushOutput("</table>\n</td>\n");
	}

	public function empire_switch($empire_list,$emp_upd) {

$out =<<<col1_r
<form action="{$this->BASE_FILE}" method='post'>
	<TR bgcolor="#AAAAAA">
		<TD colspan=3>Changement de nom d'empire: (Noms simplifiés)</TD>
		<TD rowspan=3><input name='emp_upd' type=submit value='Changer'></TD>
	</TR>
	<TR bgcolor="{$this->cols_cls[1]}">
		<TD>Original:</TD>
		<TD colspan=2>
		<select name='emp_orig'>
			<option name=''>[Selectionner un empire]</option>
col1_r;
		$this->PushOutput($out);
		$this->SelectOptions($empire_list,-1);

$out =<<<col2_r
		</select>
		</TD>
	</TR>
	<TR bgcolor="{$this->cols_cls[0]}">
		<TD>Nouveau:</TD>
		<TD colspan=2>
		<select name='emp_new'>
			<option name=''>[Supprimer l'empire]</option>
col2_r;
		$this->PushOutput($out);
		$this->SelectOptions($empire_list,-1);

$out =<<<col3_r
		</select>
		</TD>
	</TR>
</form>
col3_r;
		if ($emp_upd)
$out =<<<upd
	<TR bgcolor="{$this->cols_cls[1]}">
		<TD colspan=4>{$emp_upd} joueurs modifié avec le nouvel empire.</TD>
	</TR>
upd;

		$this->PushOutput($out);
	}

	public function empire_wars($empire_list,$emp_war) {

$out =<<<col1_r
<form action="{$this->BASE_FILE}" method='post'>
	<TR bgcolor="#AAAAAA">
		<TD colspan=3>Déclaration de guerre à un empire: (Noms simplifiés)</TD>
		<TD rowspan=3><input name='emp_war' type=submit value='Changer'></TD>
	</TR>
	<TR bgcolor="{$this->cols_cls[1]}">
		<TD colspan=3 align=center>
		<select name='emp'>
			<option name=''>[Selectionner un empire]</option>
col1_r;
		$this->PushOutput($out);
		$this->SelectOptions($empire_list,-1);

$out =<<<col2_r
		</select>
		</TD>
	</TR>
	<TR bgcolor="{$this->cols_cls[0]}">
		<TD colspan=3>
			<input type=radio name="r_war" value="5" checked>Déclarer la guerre</input>
			<input type=radio name="r_war" value="0">Déclarer la paix</input>
		</TD>
	</TR>
</form>
col2_r;

		if ($emp_war)
$out .=<<<upd
	<TR bgcolor="{$this->cols_cls[1]}">
		<TD colspan=4>{$emp_war} joueurs modifié avec le nouveau status.</TD>
	</TR>
upd;

		$this->PushOutput($out);
	}

	public function empire_allywars($allysnb,$warsnb) {

$out =<<<col1_r
<form action="{$this->BASE_FILE}" method='post'>
	<TR bgcolor="#AAAAAA">
		<TD colspan=3>Remettre à zéro les information sur les alliés/guerres</TD>
		<TD><input name='emp_allywars' type=submit value='RAZ'></TD>
	</TR>
col1_r;

		if ($allysnb>=0 || $warsnb>=0)
$out .=<<<upd
	<TR bgcolor="{$this->cols_cls[1]}">
		<TD colspan=4>{$allysnb} joueurs modifié avec le 'nouveau' status d'allié.</TD>
	</TR>
	<TR bgcolor="{$this->cols_cls[0]}">
		<TD colspan=4>{$warsnb} joueurs modifié avec le 'nouveau' status d'ennemis.</TD>
	</TR>
upd;

		$this->PushOutput($out);
	}

	public function DoOutput($include_menu=true, $include_header=true) {
		$this->PushOutput("\n</tr></table></html>");
		parent::DoOutput(); // false false ? header menu
	}

/**
 *
 * @return tpl_eadmin
 */
	static public function getinstance() {
		if ( ! DataEngine::_tpl_defined(get_class()) )
			DataEngine::_set_tpl(get_class(),new self());

			return DataEngine::tpl(get_class());
	}
}