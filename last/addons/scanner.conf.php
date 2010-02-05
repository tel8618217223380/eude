<?php
/**
 * $Author: Alex10336 $
 * $Revision: 277 $
 **/

class scanner_addons implements addon_config {

    public function Is_Enabled () {
        if (!Members::CheckPermsKey('CARTOGRAPHIE_SCANNER'))
            Members::CheckPermsKeyAdd('CARTOGRAPHIE_SCANNER', AXX_ROOTADMIN);
        
        return true;
    }
    public function CheckPerms () {
        return Members::CheckPerms('CARTOGRAPHIE_SCANNER');
    }

    public function Get_Menu () {

        // juste la partie 'sous-menu'
        $submenu = array(
                array('%ADDONS_URL%scanner/index.php','%IMAGES_URL%test.png','DataEngine::CheckPerms("CARTOGRAPHIE_SCANNER")'), // sous-menu 1
        );

        return array('insertafter' => 'carto', // empty for first.(ceux déjà inclus: carto,perso,addon,admin,forum, et logout)
                'id' => 'idsample', // doit être unique ! (pas écraser qui que ce soit d'autre)
                'onlysub' => true, // ajout a la fin du menu existant (champ 'id' ignoré)
                'menu' => $submenu);
    }

    public function InSubAddonMenu () {
        return false;
    }
    public function OnDeleteUser($user) {
        return true;
    }
    public function OnNewUser($user) {
        return true;
    }
    public function GetCustomPerms() {
        return array('CARTOGRAPHIE_SCANNER'=>'Scanneur de vortex automatique');
    }
}