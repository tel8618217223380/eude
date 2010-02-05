<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 **/
if (!SCRIPT_IN) die('Need by included');

/*
$tpl = new tpl_carte($map);
$tpl->navigation();
$tpl->maparea();
$tpl->itineraire_header();
$tpl->itineraire_form();
$tpl->Parcours_Start();
$tpl->Parcours_Row();
$tpl->Parcours_End();
$tpl->Legend();
$tpl->javascript();
$tpl->DoOutput();
*/
class tpl_carte extends output {
    protected $BASE_FILE = '';
    private $map='';

    public function __construct() {
        $this->BASE_FILE = ROOT_URL."Carte.php";
        $this->map = map::getinstance();
        parent::__construct();
    }

    public function navigation() {
        $ennemis_bool		= array('1' => 'enemy', '0' => 'ga');
        $allys_bool		= array('1' => 'own', '0' => 'neutral');
        $pnj_bool		= array('1' => 'npc', '0' => 'neutral');
        $onoff_bool		= array('0' => 'Off', '1' => 'On');
        $invert_bool		= array('0' => '1', '1' => '0');

        $onoff_sc		= $onoff_bool[(($this->map->sc+1)%2)];
        $onoff_vortex           = $onoff_bool[$this->map->vortex];
        $onoff_joueur		= $onoff_bool[$this->map->joueur];
        $onoff_planete		= $onoff_bool[$this->map->planete];
        $onoff_asteroide	= $onoff_bool[$this->map->asteroide];
        $onoff_ennemis		= $onoff_bool[$this->map->ennemis];
        $onoff_allys		= $onoff_bool[$this->map->allys];
        $onoff_pnj		= $onoff_bool[$this->map->pnj];
        $img_ennemis		= $ennemis_bool[$this->map->ennemis];
        $img_allys		= $allys_bool[$this->map->allys];
        $img_pnj		= $pnj_bool[$this->map->pnj];

        $get_sc			= $invert_bool[$this->map->sc];
        $get_vortex		= $invert_bool[$this->map->vortex];
        $get_joueur		= $invert_bool[$this->map->joueur];
        $get_planete		= $invert_bool[$this->map->planete];
        $get_asteroide          = $invert_bool[$this->map->asteroide];
        $get_ennemis		= $invert_bool[$this->map->ennemis];
        $get_allys		= $invert_bool[$this->map->allys];
        $get_pnj		= $invert_bool[$this->map->pnj];
        $helpmsg = <<<MSG
            <b>Utilisation de la carte:</b><br>
Clique Gauche: Sélection du point d'origine<br>
Clique Droit: Sélection du point d'arrivé<br>
Maj/Ctrl + Clique: Visualisation du détail du système
MSG;
        $helpmsg 				= bulle($helpmsg);
        $msg_taill_inc	= bulle('Taille de carte +');
        $msg_taill_dec	= bulle('Taille de carte &#151;');
        $msg_cls	= bulle('Couleurs de carte');
        $msg_all_on	= bulle('Tout activer');
        $msg_all_off	= bulle('Tout désactiver');
        $msg_vortex	= bulle("Vortex: {$onoff_vortex}");
        $msg_joueur	= bulle("Joueurs: {$onoff_joueur}");
        $msg_planete	= bulle("Planètes: {$onoff_planete}");
        $msg_asteroide	= bulle("Astéroïdes: {$onoff_asteroide}");
        $msg_ennemis	= bulle("Ennemis: {$onoff_ennemis}");
        $msg_allys	= bulle("Alliés: {$onoff_allys}");
        $msg_pirate	= bulle("Flottes pirate: {$onoff_pnj}");
        $msg_search1	= bulle('Choix empire/joueur');
        $msg_search2	= bulle('Touche Entrée pour faire la recherche');
        $taille_inc	= ($this->map->taille+100);
        $taille_dec	= ($this->map->taille-100);
        $search         = ( isset ($_SESSION['search']) ? $_SESSION['search']: '');

        $can_search     = (DataEngine::CheckPerms('CARTE_SEARCH'));
        $nav_size       = $can_search ? 1000:540;
        $out = <<<NAV
<div id="Map_Entete" style="width:100%; height:30px; top:50px; position:absolute;">
	<TABLE id="Map_Entete" width={$nav_size}px>
		<TR id="Map_Entete">	
			<TD id="Map_Entete">
				<A id="Map_Btn" {$msg_taill_inc} HREF="{$this->BASE_FILE}?taille={$taille_inc}">&nbsp;+&nbsp;</A>
				&nbsp;&nbsp;
				<A id="Map_Btn" {$msg_taill_dec} HREF="{$this->BASE_FILE}?taille={$taille_dec}">&#151;</A>
			</TD>
			<TD id='Map_Entete'><img id="Map_Btn" {$helpmsg} width=20 height=20 src='%IMAGES_URL%help.png'></TD>
			<TD id="Map_Entete">
				<A {$msg_cls} HREF="{$this->BASE_FILE}?sc={$get_sc}">
				<img id="Map_Btn" width=20 height=20 src="%IMAGES_URL%Btn-Couleur-{$onoff_sc}.png"></A>
			</TD>
			<TD id="Map_Entete"><A id="Map_Btn" {$msg_all_on} HREF="{$this->BASE_FILE}?AllSwitch=1"><img width=10 height=10 src="%IMAGES_URL%Btn-Vortex-On.png"><img width=10 height=10  src="%IMAGES_URL%Btn-Planete-On.png"><img width=10 height=10  src="%IMAGES_URL%Btn-Joueur-On.png"><img width=10 height=10  src="%IMAGES_URL%Btn-Asteroide-On.png"></A><br/><A id="Map_Btn" {$msg_all_off} HREF="{$this->BASE_FILE}?AllSwitch=0"><img width=10 height=10 src="%IMAGES_URL%Btn-Vortex-Off.png"><img width=10 height=10  src="%IMAGES_URL%Btn-Planete-Off.png"><img width=10 height=10  src="%IMAGES_URL%Btn-Joueur-Off.png"><img width=10 height=10  src="%IMAGES_URL%Btn-Asteroide-Off.png"></A></TD>
			<TD id="Map_Entete">
				<A id="Map_Btn" {$msg_vortex} HREF="{$this->BASE_FILE}?vortex={$get_vortex}">
				<img width=18 height=18 src="%IMAGES_URL%Btn-Vortex-{$onoff_vortex}.png"></A>
			</TD>
			<TD id="Map_Entete">
				<A id="Map_Btn" {$msg_joueur} HREF="{$this->BASE_FILE}?joueur={$get_joueur}">
				<img id="Map_Btn" width=18 height=18 src="%IMAGES_URL%Btn-Joueur-{$onoff_joueur}.png"></A>
			</TD>
			<TD id="Map_Entete">
				<A id="Map_Btn" {$msg_planete} HREF="{$this->BASE_FILE}?planete={$get_planete}">
				<img width=18 height=18 src="%IMAGES_URL%Btn-Planete-{$onoff_planete}.png"></A>
			</TD>
			<TD id="Map_Entete">
				<A id="Map_Btn" {$msg_asteroide} HREF="{$this->BASE_FILE}?asteroide={$get_asteroide}">
				<img width=18 height=18 src="%IMAGES_URL%Btn-Asteroide-{$onoff_asteroide}.png"></A>
			</TD>
			<TD id="Map_Entete">
				<A id="Map_Btn" {$msg_ennemis} HREF="{$this->BASE_FILE}?ennemis={$get_ennemis}">
				<img width=18 height=18 src="%IMAGES_URL%fleet_{$img_ennemis}.gif"></A>
			</TD>
			<TD id="Map_Entete">
				<A id="Map_Btn" {$msg_allys} HREF="{$this->BASE_FILE}?allys={$get_allys}">
				<img width=18 height=18 src="%IMAGES_URL%fleet_{$img_allys}.gif"></A>
			</TD>
			<TD id="Map_Entete">
				<A id="Map_Btn" {$msg_pirate} HREF="{$this->BASE_FILE}?pnj={$get_pnj}">
				<img width=18 height=18 src="%IMAGES_URL%fleet_{$img_pnj}.gif"></A>
			</TD>
			<TD id="coord2">&nbsp;Coordonnée&nbsp;</td>
			<td id="Coord">&nbsp;0000&nbsp;</td>	
NAV;
        if (DataEngine::CheckPerms('CARTE_SEARCH'))
            $out.=<<<SEARCH
<form id="searchempire" action="Carte.php" method="post" OnSubmit="return Navigateur.DoSearch();">
			<td id="Map_Entete" {$msg_search1} align=right><input type=radio name="type" value="emp"> Empire <input type=radio name="type" value="jou" checked> Joueur :</td>
			<td id="Map_Entete" {$msg_search2}><input id="Map_Itineraire" type=text name=search value="{$search}"></td>
</form>
SEARCH;
        $out.=<<<f
                        </TR>
	</TABLE>
</div>
f;

        $this->PushOutput($out);
    }

    public function maparea() {
        $img = 'img.php';
//        $img = '%IMAGES_URL%Btn-Joueur-Off.png';
        $rnd	= time();
        $out = <<<MAP

	<!--// Tracé du fond //-->
	<div id="ajaxstatus" style="font-size:14px; top:80px; left:0px; position:absolute; color:white"></div>
	<div id="divcarteunivers" style="font-size:2px; top:80px; left:0px; width:{$this->map->taille}px; height:{$this->map->taille}Px; position:absolute; cursor: crosshair; text-align:center">
	<img id="carteunivers" src="{$img}?{$rnd}">
	</div>   			
	<div id="cibleurV" class="cibleur" style="width:1px; height:{$this->map->taille}px; z-index:3; position:absolute; visibility:hidden; font-size: 2px; background-color: #FFFFFF;cursor: crosshair">&nbsp;</div>
	<div id="cibleurH" class="cibleur" style="width:{$this->map->taille}px; height:1px; z-index:3; position:absolute; visibility:hidden; font-size: 2px; background-color: #FFFFFF;cursor: crosshair">&nbsp;</div>

MAP;
        $this->PushOutput($out);
    }

    public function itineraire_header() {
        $out = <<<iti_h
	<div id="AjaxCarteDetails"
	style="z-index:6; position:absolute; left:{$this->map->taille}px; top:80px; visibility:hidden; background-color:black; color:white">
	</div>
	<div id="Map_Itineraire" style="position:absolute; left:{$this->map->taille}px; top:80px;">
		<form name="calculer" method="post" action="Carte.php">
			<Table id="Map_Itineraire" style="width:400px">
				<tr>
					<td id="Map_Itineraire" colspan=4 align=center>Navigateur</td>
				</tr>
				<tr id="Map_Itineraire">
					<td id="Map_Itineraire">
						Parcours:
					</td>
					<td id="Map_Itineraire" colspan=2>
						<select name="loadfleet" id="fleet">
							<option value=0>[Sélectionner votre parcours]</option>
iti_h;
        $this->PushOutput($out);
    }


    public function itineraire_form() {
        $checked = $this->map->inactif ? " checked": "";
        $msg_load=bulle('Charger parcours');
        $msg_save=bulle('Enregistrer parcours');
        $msg_del =bulle('Supprimer parcours');
        $msg_inv =bulle('Intervertir les coords');
        $out = <<<iti_h
					</select>
					</td>
					<td id="Map_Itineraire" align=right nowrap>
						<a href="javascript:void(0);" OnClick="Navigateur.LoadFleet();" {$msg_load}>C</a>-<a href="javascript:void(0);" OnClick="Navigateur.SaveFleet();" {$msg_save}>E</a>-<a  href="javascript:void(0);" OnClick="Navigateur.DelFleet();" {$msg_del}>S</a></td>
				</tr>
				<tr id="Map_Itineraire">
					<td id="Map_Itineraire" colspan=2 nowrap>Système de départ:</td>
					<td id="Map_Itineraire" align=center>
						<input id="Map_Itineraire" MAXLENGTH=5 type="text" name="coorin" style="width:50;" value="{$this->map->IN}">
					</td>
					<td id="Map_Itineraire" rowspan=2 align=right style="cursor: pointer" OnClick="Navigateur.invertcoords();" {$msg_inv}><a href="javascript:void(0);"><-<br/>|<br/><-</a></td>
				</tr>
				<tr id="Map_Itineraire">
					<td id="Map_Itineraire" colspan=2 nowrap>Système d'arrivée:</td>
					<td id="Map_Itineraire" align=center>
						<input id="Map_Itineraire" MAXLENGTH=5 type="text" name="coorout" style="width:50;" value="{$this->map->OUT}">
					</td>
				</tr>
				<tr id="Map_Itineraire">
					<td id="Map_Itineraire" colspan=3>Utiliser les vortex "Inactif"</td>
					<td id="Map_Itineraire" align=center>
						<input id="Map_Itineraire" type="checkbox" name="inactif"{$checked}>
					</td>
				</tr>
				<tr id="Map_Itineraire">
					<td id="Map_Itineraire" align=center colspan=4>
						<select name="method" id="fleet">
							<option value="1">1 vortex max (calcul rapide)</option>
							<option value="2">2 vortex max (normal)</option>
							<option value="3">3 vortex max (calcul 'très' lent)</option>
							<option value="10" selected>Au plus proche (très speed)</option>
                                                </select>
						<input border=0 src="./Images/Btn-Itineraire.png" type=image Value=submit align="middle" onclick="document.getElementsByName('loadfleet')[0].selectedIndex=0;">
					</td>
				</tr>
iti_h;
        $this->PushOutput($out);
    }

    public function Parcours_Start($ss) {
        $out = <<<ps
				<TR id="Map_Itineraire">
					<TD id="Map_Itineraire">Départ</TD>
					<TD id="Map_Itineraire" align=center colspan=2>{$ss}</TD>
					<TD id="Map_Itineraire" align=center>0 pc</TD>
				</TR>
ps;
        $this->PushOutput($out);
    }

    public function Parcours_Row($vortex,$IN,$OUT,$dist) {
        $out = <<<pr
				<TR id="Map_Itineraire">
					<TD id="Map_Itineraire">Vortex {$vortex}</TD>
					<TD id="Map_Itineraire" align=center colspan=2>{$IN} <-> {$OUT}</TD>
					<TD id="Map_Itineraire" align=center>{$dist} pc</TD>
				</TR>
pr;
        $this->PushOutput($out);
    }

    public function Parcours_End($d,$db,$dt,$dd,$p) {
        $out = <<<pe
				<TR id="Map_Itineraire">
					<TD id="Map_Itineraire">Arrivée</TD>
					<TD id="Map_Itineraire" align=center colspan=2>{$p}</TD>
					<TD id="Map_Itineraire" align=center>{$d} pc</TD>
				</TR>
				<TR id="Map_Itineraire">
					<TD id="Map_Itineraire">Différence</TD>
					<TD id="Map_Itineraire" align=right>{$db} pc</TD>
					<TD id="Map_Itineraire" align=left>-{$dd} pc =</TD>
					<TD id="Map_Itineraire" align=center>{$dt} pc</TD>
				</TR>
pe;
        $this->PushOutput($out);
    }

    public function Legend() {
        $map = map::getinstance();
        $out = <<<h
            </Table>
		</form>
	<Table>
		<tr>
			<td id="legendheader" colspan=2>Légende</td>
		</tr>
h;
        $legend  = Config::GetMapColor( $map->itineraire ? 0: $map->sc+1 );
        foreach($legend['l'] as $k => $v)
            $out .= <<<l
		<tr id="legend">
			<td id="legend" style="background-color: {$legend['c'][$k]};">&nbsp;</td>
			<td id="Map_Itineraire">&nbsp;{$v}&nbsp;</td>
		</tr>
l;
        $out .=<<<f
            </Table>
	</div>
f;
        $this->PushOutput($out);
    }

    public function javascript($TabData) {
        $TailleCase = floor($this->map->taille/100);
        $out = <<<JS

<script type="text/javascript" src="./Script/carte.js?{$this->version}"></script>
<script type='text/javascript'>
                {$TabData}
	var Carte = new CCarte({$TailleCase},100,TabData);
	Carte.init();
	delete TabData;
</script>
JS;
        $this->PushOutput($out);
    }

    public function DoOutput($include_menu=true, $include_header=true) {
        parent::DoOutput();
    }

    /**
     * @return tpl_carte
     */
    static public function getinstance() {
        if ( ! DataEngine::_tpl_defined(get_class()) )
            DataEngine::_set_tpl(get_class(),new self());

        return DataEngine::tpl(get_class());
    }
}