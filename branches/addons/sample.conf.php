<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 **/

class sample_addons implements addon_config {

    public function Is_Enabled () { return DE_DEMO && $this->CheckPerms(); }

    public function Get_Menu () {

        // menu simple.
        $menu = array('%ADDONS_URL%sample/index.php','%IMAGES_URL%test.png',125,'DE_DEMO || DataEngine::CheckPerms(AXX_ROOTADMIN)', null);

        // menu + sous menu
        $menu2 = array('%ADDONS_URL%sample/index.php','%IMAGES_URL%test.png',125,'DataEngine::CheckPerms(AXX_ROOTADMIN)',
            array(
            array('%ADDONS_URL%sample/index.php','%IMAGES_URL%test.png','true'), // sous-menu 1
            array('%ROOT_URL%index.php','%IMAGES_URL%btn-cartographie.png','true'), // sous-menu 2
            ),
        );

        // juste la partie 'sous-menu'
        $submenu = array(
            array('%ADDONS_URL%sample/index.php','%IMAGES_URL%test.png','true'), // sous-menu 1
            array('%ROOT_URL%index.php','%IMAGES_URL%btn-cartographie.png','true'), // sous-menu 2
        );

        return array('insertafter' => 'perso', // empty for first.(ceux déjà inclus: carto,perso,addon,admin,forum, et logout)
        'id' => 'idsample', // doit être unique ! (pas écraser qui que ce soit d'autre)
        'onlysub' => false, // ajout a la fin du menu existant (champ 'id' ignoré)
        'menu' => $menu);
    }

    /**
     * Si actif, le paramètre du menu 'insertafter' doit être 'addon'
     * ainsi que le paramètre 'onlysub' a true
     * @return boolean
     */
    public function InSubAddonMenu () { return false; }
    
    public function CheckPerms () { return DE_DEMO || DataEngine::CheckPerms(AXX_ROOTADMIN); }

    public function OnDeleteUser($user) {
//        FB::info($user,'addons::OnDeleteUser');
        return true;
    }
    public function OnNewUser($user) {
//        FB::info($user,'addons::OnNewUser');
        return true;
    }
}

