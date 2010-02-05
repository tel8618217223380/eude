<?php
/**
 * Empire Universe 2 - Data Engine
 *
 * Basé sur le travail de Christophe Couprie alias Elessar81
 * Corrigé, amélioré sur autorisation express par alex10336
 *
 * Dernière modification:
 * $Author: Alex10336 $
 * $Revision: 254 $
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 **/

final class Config implements iDataEngine_Config {
    static private $connexion;
    /**
     * @var localhost Adresse du serveur
     */
    static protected $BaseMysql = '%localhost%';
    /**
     * @var user Nom d'utilisateur pour le serveur mysql
     */
    static protected $BaseUser  = '%user%';
    /**
     * @var pass Mot de passe pour le serveur mysql
     */
    static protected $BasePass  = '%pass%';
    /**
     * @var database Nom de la base de donnée utilisé par le serveur mysql
     *   (en général le même que $BaseUser)
     */
    static protected $BaseName  = '%database%';
    /**
     * fonction d'initialisation spéciale...
     */
    static function init() {
        /**
         * En cas de configuration serveur particulière.
         * Et que la détection automatique aurait échoué.
         * Symptome en cas d'échec:
         * - Page de login blanche
         * - La session ne reste pas (rien ne marche, donc)
         * @const ROOT_URL
         * @link http://app216.free.fr/eu2/tracker/view.php?id=160
         * @example
         * [code]
         * define('ROOT_URL', '/');
         * define('ROOT_URL', '/DataEngine/');
         * [/code]
         * enlever les '//' au début dun'e ligne pour activer le paramètre
         */

        //define('ROOT_URL', '/');


        /**
         * préfixe de table a changer pour permettre plusieurs DataEngine sur la même base...
         * par défaut vide
         * @staticvar SQL_PREFIX_ ''
         */
        define('SQL_PREFIX_','');
    }
    /**
     * @return string Lien vers le forum
     */
    static function GetForumLink() {
        return '';
    }
    /**
     * Grade par défaut
     * 'id' affiché sur la page "Membre.php" a droite
     * @return integer grade id
     */
    static function GetDefaultGrade() {
        return 3;
    }
    /**
     * Active la possibilité au membre de l'empire de créer un compte eux même.
     * @return boolean
     */
    static function CanRegister() {
        return false;
    }
    /**
     * Définir ici le nom <b>exact</b> de votre empire
     * @return string(100) Nom de l'empire
     */
    static function GetMyEmpire() {
        return '%empire%';
    }
    /**
     * Définir  ici le nom <b>exact</b> de(s) l'empire(s) ennemi
     * @since 1.4.1
     * @return array nom(s) de(s) l'empire(s) ennemi.
     */
    static function GetEmpireEnnemy() {
        $listing = array();
        $listing[] = '<ennemis>';
        //$listing[] = '';

        return $listing;
    }
    /**
     * Définir  ici le nom <b>exact</b> de(s) l'empire(s) allié.
     * @since 1.4.1
     * @return array nom(s) de(s) l'empire(s) allié.
     */
    static function GetEmpireAllys() {
        $listing = array();
        $listing[] = '<alliés>';
        //$listing[] = '';

        return $listing;
    }
    /**
     * Temps max de recherche avant abandon pour le meilleurs recensé
     * @since 1.4.1
     * @return integer default: 0 (ie. Temps max autorisé par le serveur ~20/30sec.)
     */
    static public function Parcours_Max_Time() {
        return 0;
    }
    /**
     * Calcul "Au plus proche", définit le rayon max de recherche autour du point de départ/arrivé
     * Ne pas mettre trop haut. Ça n'aurait plus d'intérêts, et alourdirait plus qu'autre chose
     * @since 1.4.1
     * @return integer default: 5 , Max conseillé: 8, min conseillé: 3
     */
    static public function Parcours_Nearest() {
        return 5;
    }
    /**
     *
     * @example
     * [code]
     * return '';             // pour tout serveur sans restriction
     * return 'australis.fr'; // pour australis
     * return 'borealis.fr';  // pour borealis
     * return 'beta.de';      // pour test sur .de
     * return 'eu2.com';      // pour eu2 anglais
     * [/code]
     */
    static public function eude_srv() {
        return '';
    }
    /**
     * Couleurs personnalisable
     * Lien pouvant être utile
     * @link http://www.colorschemer.com/online.html
     * @param integer $id <code>$map->itineraire ? 0: $map->sc+1</code>
     * @return array
     */
    static function GetMapColor($id) {
        $result = array('c' => array(), 'l' => array());
        switch ($id) {
            case 0: // Mode Itinéraire:
                $result['c']['0']  = '#232323'; //$result['l']['0']  = 'Centre de communication';
                $result['c']['1']  = '#444444';
                $result['l']['1']  = 'Astre quelconque';
                $result['c']['2']  = '#3333FF';
                $result['l']['2']  = 'Mes colonies';

                $result['c']['20'] = '#FF0080';
                $result['l']['20'] = 'Départ...';
                $result['c']['21'] = '#00DD00';
                $result['l']['21'] = 'Arrivée.';
                $result['c']['22'] = '#FF9933';
                $result['l']['22'] = 'Passage par vortex.';
                $result['c']['24'] = '#FF9933'; //$result['l']['24'] = 'Navigation \'Warp\' normale';
                $result['c']['25'] = '#787878'; //$result['l']['25'] = 'Navigation par vortex.';
                return $result;
                break;
            case 1: // Mode plein de couleurs partout
                $result['c']['0']  = '#232323'; //$result['l']['0']  = 'Centre de communication';
                $result['c']['1']  = '#FF8000';
                $result['l']['1']  = 'Joueurs de l\'empire';
                $result['c']['2']  = '#008800';
                $result['l']['2']  = 'Mes colonies';
                $result['c']['3']  = '#444444';
                $result['l']['3']  = 'Joueurs';
                $result['c']['4']  = '#3333FF';
                $result['l']['4']  = 'Vortex';
                $result['c']['5']  = '#787878';
                $result['l']['5']  = 'Astéroïde';
                $result['c']['6']  = '#00DD00'; //$result['l']['6']  = 'Colonie + Autre';
                $result['c']['7']  = '#FF00FF'; //$result['l']['7']  = 'Joueur de l\'empire + Autre';
                $result['c']['11'] = '#00DD00';
                $result['l']['11'] = 'Alliés';
                $result['c']['8']  = '#DD0000';
                $result['l']['8']  = 'Joueurs ennemi';
                $result['c']['9']  = '#FFFF00';
                $result['l']['9']  = 'Flottes PNJ';
                $result['c']['10'] = '#FF00FF';
                $result['l']['10'] = 'Résultat de recherche';
                return $result;
                break;
            case 2: // Mode couleur minimale:
                $result['c']['0']  = '#232323'; //$result['l']['0']  = 'Centre de communication';
                $result['c']['1']  = '#FF8000';
                $result['l']['1']  = 'Joueurs de l\'empire';
                $result['c']['2']  = '#008800';
                $result['l']['2']  = 'Mes colonies';
                $result['c']['3']  = '#444444';
                $result['l']['3']  = 'Astre quelconque';
                $result['c']['4']  = '#444444'; //$result['l']['4']  = 'Vortex';
                $result['c']['5']  = '#444444'; //$result['l']['5']  = 'Astéroïde';
                $result['c']['6']  = '#00DD00'; //$result['l']['6']  = 'Colonie + ?';
                $result['c']['7']  = '#444444'; //$result['l']['7']  = 'Joueur de l\'empire + Autre';
                $result['c']['11'] = '#00DD00';
                $result['l']['11'] = 'Alliés';
                $result['c']['8']  = '#444444'; //$result['l']['8']  = 'Joueurs ennemi';
                $result['c']['9']  = '#444444'; //$result['l']['9']  = 'Flottes PNJ';
                $result['c']['10'] = '#FF00FF';
                $result['l']['10'] = 'Résultat de recherche';
                return $result;
                break;
        }
    }

    /**
     * Connexion a la base de donnée.
     * @return mysqli_driver
     */
    static function DB_Connect() {
        if (self::$connexion) return self::$connexion;

        self::$connexion = mysql_connect(self::$BaseMysql,self::$BaseUser,self::$BasePass)
                or die(mysql_error());
        mysql_select_db(self::$BaseName)  or die(mysql_error());
        return self::$connexion;
    }
}