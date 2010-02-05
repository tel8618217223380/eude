<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 **/

require_once(ADDONS_PATH.'addons.php');

class addons {
    private $addons_list;
    private $addons_menu=false;
    static private $instance;

    public function __construct() {
        $this->addons_list = array();
        foreach( array_diff(scandir(ADDONS_PATH), array('.','..')) as $file ) {
            if (is_dir($file)) continue;
            $addon = basename($file, '.conf.php');
            if ("{$addon}.conf.php" == $file) {
                include_once(ADDONS_PATH.$file);
                $class_name = "{$addon}_addons";
                $class = new $class_name;
                if ($class->Is_Enabled())
                    $this->addons_list[$addon] = $class;
                if ($class->InSubAddonMenu()) $this->addons_menu = true;
            }
        }
    }

    /**
     * Vérifie si l'addon est activé, pas les perms !
     * @param string $addon_name Nom de 'code' de l'addon (=nom du fichier conf)
     * @return boolean
     */
    public function Is_installed($addon_name) {
        return (array_key_exists($addon_name, $this->addons_list) !== false);
    }
    /**
     * Récupère la classe d'un addons
     * @param string $addon_name
     * @return addon_config
     */
    public function Get_Addons($addon_name) {
        return (array_key_exists($addon_name, $this->addons_list) !== false) ? $this->addons_list[$addon_name]: false;
    }

    public function IncludeAddonMenu() {
        return $this->addons_menu;
    }

    public function Parse_Menu($base) {
        $new_menu = array();

        // ajout d'un menu en première place ?
        foreach ($this->addons_list as $addon => $class) {
            $addons_menu = $class->Get_Menu();
            if ($addons_menu['insertafter'] == '' && !$addons_menu['onlysub'])
                $new_menu[$addons_menu['id']] = $addons_menu['menu'];
        }

        // traitement avec le menu de base
        foreach ($base as $id => $menu) {
            $new_menu[$id] = $menu;
            foreach ($this->addons_list as $addon => $class) {
                $addons_menu = $class->Get_Menu();
                if ($addons_menu['insertafter'] == $id && !$addons_menu['onlysub'])
                    $new_menu[$addons_menu['id']] = $addons_menu['menu'];
                elseif($addons_menu['insertafter'] == $id && $addons_menu['onlysub'])
                    foreach($addons_menu['menu'] as $submenu)
                        array_push($new_menu[$id][4], $submenu);
            }
        }
        return $new_menu;
    }
    /**
     *
     * @param string Nom d'utilisateur
     * @return boolean
     */
    public function DeleteUser($user) {
        foreach ($this->addons_list as $addon => $class) {
            if (!$class->OnDeleteUser($user)) {
                trigger_error('Delete user from '.$addon.' failed',E_ERROR);
                return false;
            }
        }
        return true;
    }
    /**
     * Routine de création d'utilisateur
     * @param string Nom d'utilisateur
     * @return boolean
     */
    public function NewUser($user) {
        foreach ($this->addons_list as $addon => $class) {
            if (!$class->OnNewUser($user)) {
                trigger_error('New user from '.$addon.' failed',E_ERROR);
                return false;
            }
        }
        return true;
    }
    /**
     * Récupère les niveau d'accès perso
     * @return array ($key => $humain_value)
     */
    public function CustomPerms() {
        $cp=array();
        foreach ($this->addons_list as $addon => $class) {
            if (($arr=$class->GetCustomPerms()))
                foreach($arr as $k => $v)
                    $cp[$k] = $v;
        }
        return $cp;
    }

    /**
     * Init une classe addons
     * @return addons
     */
    static public function getinstance() {
        if ( ! self::$instance )
            self::$instance = new self();

        return self::$instance;
    }
}