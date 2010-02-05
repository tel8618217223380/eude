<?php
/**
 * $Author$
 * $Revision$
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 **/

interface addon_config {
    /**
     * Active ou non l'extension
     * @return  boolean
     */
    public function Is_Enabled ();
    /**
     * Définit un menu pour l'addons
     * @return array array('page', 'image', taille(width), permissions/pré-requis, (array)sous-menu)
     */
    public function Get_Menu ();
    /**
     * Met en place un menu spécial 'addons'
     * @return boolean
     */
    public function InSubAddonMenu ();
    /**
     * Permission prérequise avant toute action.
     * @return boolean
     */
    public function CheckPerms ();

    /**
     * Routine de suppression pour les addons
     * @param string Nom d'utilisateur
     * @return boolean
     */
    public function OnDeleteUser($user);
    /**
     * Routine de création pour les addons
     * @param string Nom d'utilisateur
     * @return boolean
     */
    public function OnNewUser($user);
}
