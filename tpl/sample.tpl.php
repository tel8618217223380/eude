<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 **/
if (!SCRIPT_IN) die('Need by included');
/*
$tpl = tpl_sample::getinstance();
$tpl->DoOutput();
*/
class tpl_sample extends output {
    protected $BASE_FILE = '';

    public function __construct() {
        $this->BASE_FILE = ROOT_URL.'sample.php';

        parent::__construct();
    }

    public function AddRow($ligne) {
        $this->PushOutput($out);
    }

    /**
     * Génère la page
     * @param boolean,array $include_menu Inclure le menu ? (voir son propre menu)
     * @param boolean $include_header Inclure l'entete ?
     */
    public function DoOutput($include_menu=true, $include_header=true) {
        $this->PushOutput('');
        parent::DoOutput($include_menu, $include_header);
    }
    /**
     *
     * @return tpl_sample
     */
    static public function getinstance() {
        if ( ! DataEngine::_tpl_defined(get_class()) )
            DataEngine::_set_tpl(get_class(),new self());

        return DataEngine::tpl(get_class());
    }
}