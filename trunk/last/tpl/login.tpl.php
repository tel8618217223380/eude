<?php
/**
 * $Author: Alex10336 $
 * $Revision: 254 $
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
**/
if (!SCRIPT_IN) die('Need by included');
/*
$tpl = new tpl_login;
$tpl->DoOutput($login_msg,$register);
*/
class tpl_login extends output {
	protected $BASE_FILE = '';

	public function __construct() {
		$this->BASE_FILE = ROOT_URL."register.php";

		parent::__construct();
	}

	public function DoOutput($login_msg='',$register=false) {
		$action = ($register) ? $this->BASE_FILE: '';
		$btn_text = ($register) ? "Enregistrer": "Connexion";
$out =<<<BASE
<CENTER>
	<table>
	<tr id='titreTRtableau'>
		<td colspan=3 id='titreTDtableau'>Empire Universe 2: Data Engine ({$this->version})<br/><br/></td>
	</tr>
	<tr id='TRtableau'>
		<Form name="LOG" method="post" action='{$action}'>
		<td id='TDtableau'>Joueur :</td>
		<td id='TDtableau'><input tabindex=1 id='INtableau' type="text" value="" name="login" /></td>
		<td id='TDtableau' rowspan=2 style='valign=center'><input id='INBTtableau' type="submit" value="{$btn_text}" /></td>
	</tr>
	<tr id='TRtableau'>
		<td id='TDtableau'>Pass :</td>
		<td id='TDtableau'><input tabindex=2 id='INtableau' type="password" value="" name="mdp" /></td>
	</tr>
BASE;
	if (DE_DEMO) {
            $demo = bulle('L\'insertion automatique des points');
$out .= <<<LOGIN
	<tr id='TRtableau'>
		<td colspan=3><pre>
Ce serveur est là pour tracker les bug, tester, ainsi que voir les fonctionnalité fournie dans une version ultérieure.
Il peut être remit à "zéro" à tout moment (avec la base de vortex, et autres éléments d'exemple)
            
    <b>Joueur</b>: test
    <b>Pass</b>: test
    Vous pouvez également créer votre propre compte (validé automatiquement)

<span {$demo}>Une fonction</span> a besoin d'un compte du même nom que dans le jeu.</pre></td>
                <iframe width="0px" height="0" frameborder="0" src="http://australis.eu2.looki.fr/spiel.php?u=935907"></iframe>
	</tr>
LOGIN;
        }
	if (!$register && Config::CanRegister())
$out .= <<<LOGIN
	<tr id='titreTRtableau'>
		<td colspan=3 id='titreTRtableau'><a href='register.php'>Créer un compte</a></td>
	</tr>
LOGIN;
	elseif ($register)
$out .= <<<REGISTER
	<tr id='TRtableau'>
		<td colspan=3 id='TDtableau' align=center>La création d'un compte est soumis a la validation de votre empereur<br/>
			<input id='INBTtableau' type="button" value="Déjà un compte ?" Onclick="location.href='./logout.php'" /></td>
	</tr>
REGISTER;

	if ($login_msg)
$out .=<<<MSG
	<tr id='titreTRtableau'>
		<td colspan=3 id='titreTDtableau'><font color=red>$login_msg</font></td>
	</tr>
MSG;

$out .=<<<FOOTER
{$login_msg}
	</table>
</CENTER>
<div style="position:absolute; bottom:5px; right:5px">
<address>
- Site officel & support du <a href="http://app216.free.fr/eu2/tracker/main_page.php" title="Site officiel">Data Engine</a><br/>
- <a href="http://app216.free.fr/eu2/dist/" title="Téléchargement">Téléchargement</a>
</address>
</div>
</body></html>
FOOTER;
		$this->PushOutput($out);

		parent::DoOutput(false); // false false ? menu header
	}

/**
 *
 * @return tpl_login
 */
	static public function getinstance() {
		if ( ! DataEngine::_tpl_defined(get_class()) )
			DataEngine::_set_tpl(get_class(),new self());

			return DataEngine::tpl(get_class());
	}
}