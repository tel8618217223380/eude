<?php
/**
 * @author Alex10336
 * Dernière modification: $Id$
 * @license GNU Public License 3.0 ( http://www.gnu.org/licenses/gpl-3.0.txt )
 * @license Creative Commons 3.0 BY-SA ( http://creativecommons.org/licenses/by-sa/3.0/deed.fr )
 *
 **/
if (!SCRIPT_IN) die('Need by included');
/*
$tpl = new tpl_stats;
	$tpl->AddToRow($line["Joueur"], -2);
		$tpl->AddToRow($line2['Nb'], $line2['Type']);
	$tpl->AddToRow($ut, -1);
	$tpl->PushRow();
$tpl->DoOutput();
*/
class tpl_stats extends output {
    protected $BASE_FILE = '';

    private $colsid=0;
    private $cols = array(0=>"#CCCCCC",1=>"#D6D6D6");
    protected $total = null;
    protected $currow = '';
    protected $curtpl;

    public function __construct() {
        $this->BASE_FILE = ROOT_URL."stats.php";
        parent::__construct();
        $this->total = array_fill(-1,8,0);
        $out = <<<h
<table bgcolor='#CCCCCC' align=center>
<tr><td><a href='{$this->BASE_FILE}'>Stats Data Engine</a></td><td><a href='{$this->BASE_FILE}?act=pts'>Points</a></td></tr>
<tr><td colspan="2">
h;
        $this->PushOutput($out);
    }

    public function SetRowtpl() {
        $this->currow = <<<ROW
            <TR bgcolor="%%bgcls%%">
		<TD>%%-2%%</TD>
		<TD align=center>%%0%%</TD>
		<TD align=center>%%5%%</TD>
		<TD align=center>%%6%%</TD>
		<TD align=center>%%1%%</TD>
		<TD align=center>%%2%%</TD>
		<TD align=center>%%4%%</TD>
		<TD align=center>%%3%%</TD>
		<TD align=center>%%-1%%</TD>
	</TR>
ROW;

    }
    public function Setheader() {
        $tmp = <<<TABLE
            <TABLE bgcolor='#AAAAAA' align='center' width=900px>
TABLE;
        $this->curtpl = 'SetRowtpl';
        $this->PushOutput($tmp);
        $this->AddToRow('Utilisateur', -2);
        $this->AddToRow('Joueurs', 0);
        $this->AddToRow('Ennemis', 5);
        $this->AddToRow('Flotte PNJ', 6);
        $this->AddToRow('Vortex', 1);
        $this->AddToRow('Planètes', 2);
        $this->AddToRow('Astéroïdes', 4);
        $this->AddToRow('Autres (0?)', 3);
        $this->AddToRow('Total', -1);
        $this->PushRow(true);

    }

    public function footer() {
        $this->AddToRow('Total', -2);
        for ($i=-1; $i<7; $i++) $this->AddToRow($this->total[$i], $i); // valeur totales
        $this->PushRow();
    }

    public function SetRowtplPoints() {
        $this->currow = <<<ROW
            <TR bgcolor="%%bgcls%%">
		<TD>%%-2%%</TD>
		<TD align=center>%%Points%%</TD>
		<TD align=center>%%pts_architecte%%</TD>
		<TD align=center>%%pts_mineur%%</TD>
		<TD align=center>%%pts_science%%</TD>
		<TD align=center>%%pts_commercant%%</TD>
		<TD align=center>%%pts_amiral%%</TD>
		<TD align=center>%%pts_guerrier%%</TD>
	</TR>
ROW;

    }
    public function SetheaderPoints() {
        $tmp = <<<TABLE
            <TABLE bgcolor='#AAAAAA' align='center' width=900px>
TABLE;
        $this->curtpl = 'SetRowtplPoints';
        $this->PushOutput($tmp);
        $this->AddToRow('Utilisateur', -2);
        $this->AddToRow('Points total', 'Points');
        $this->AddToRow('Architecte', 'pts_architecte');
        $this->AddToRow('Mineur', 'pts_mineur');
        $this->AddToRow('Science', 'pts_science');
        $this->AddToRow('Commerçant', 'pts_commercant');
        $this->AddToRow('Amiral', 'pts_amiral');
        $this->AddToRow('Guerrier', 'pts_guerrier');
        $this->PushRow(true);

    }

    public function AddToRow($value, $key) {
        if (is_numeric($value)) {
            $this->total[$key] += $value;
            $value =DataEngine::format_number($value, true);
        }
        $this->currow=str_replace("%%$key%%", $value, $this->currow);
    }
    public function PushRow($bgcls=false) {
        if ($bgcls) {
            $this->AddToRow('#AAAAAA', 'bgcls');
            $colsid = 0;
        } else {
            $this->colsid++;
            $this->AddToRow($this->cols[($this->colsid%2)], 'bgcls');
            for ($i=-2; $i<8; $i++) $this->AddToRow('-', $i); // valeur par défaut
        }
        $this->PushOutput($this->currow);
        call_user_func(array($this,$this->curtpl), $this);
       // $this->curtpl();
    }

    public function DoOutput($include_menu=true, $include_header=true) {

        $this->PushOutput('</table></td></tr></table>');
        parent::DoOutput();
    }

    /**
     *
     * @return tpl_stats
     */
    static public
    function getinstance() {
        if ( ! DataEngine::_tpl_defined(get_class()) )
            DataEngine::_set_tpl(get_class(),new self());

        return DataEngine::tpl(get_class());
    }
}