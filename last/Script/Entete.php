<?php
/**
 * Empire Universe 2 - Data Engine
 *
 * Basé sur le travail de Christophe Couprie alias Elessar81
 * Corrigé, amélioré sur autorisation express par alex10336
 *
 * Dernière modification:
 * $Author: Alex10336 $
 * $Revision: 268 $
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 **/

final class Config implements iDataEngine_Config {
    static private $connexion;
    /**
     * @var localhost Adresse du serveur
     */
    static protected $BaseMysql = 'localhost';
    /**
     * @var user Nom d'utilisateur pour le serveur mysql
     */
    static protected $BaseUser  = 'eu2';
    /**
     * @var pass Mot de passe pour le serveur mysql
     */
    static protected $BasePass  = 'eu2';
    /**
     * @var database Nom de la base de donnée utilisé par le serveur mysql
     *   (en général le même que $BaseUser)
     */
    static protected $BaseName  = 'eu2';
    /**
     * fonction d'initialisation spéciale en cas de configuration serveur
     * particulière. Et que la détection automatique aurait échoué.
     * Symptome en cas d'échec:
     * - Page de login blanche
     * - La session ne reste pas (rien ne marche, donc)
     * @var ROOT_URL partie 'path' de l'adresse où je me trouve. (finit par '/')
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
         */

        define('ROOT_URL', '/eu2/eu2/');

        /**
         * préfixe de table a cha nger pour permettre plusieurs DataEngine sur la même base...
         * par défaut vide
         * @const SQL_PREFIX_ ''
         */
        define('SQL_PREFIX_','SQL_PREFIX_');
    }
    /**
     * @return string Lien vers le forum
     */
    static function GetForumLink() {
        return 'http://app216.free.fr/eu2/tracker/view_all_bug_page.php';
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
     * Définir ici le nom <b>exact</b> de votre empire.
     * @return string(100) Nom de l'empire
     */
    static function GetMyEmpire() {
//        return 'Lords of War';
        return 'Data Engine';
    }
    /**
     * Définir  ici le nom <b>exact</b> de(s) l'empire(s) ennemi
     * @return array nom(s) de(s) l'empire(s) ennemi.
     */
    static function GetEmpireEnnemy() {
        $listing = array();
        //$listing[] = 'S0LARIS';

        return $listing;
    }
    /**
     * Définir  ici le nom <b>exact</b> de(s) l'empire(s) allié.
     * @return array nom(s) de(s) l'empire(s) allié.
     */
    static function GetEmpireAllys() {
        $listing = array();
//        $listing[] = 'Lords of War';

        return $listing;
    }
    /**
     * Temps max de recherche avant abandon pour le meilleurs recensé
     * @return integer default: 0 (ie. Temps max autorisé par le serveur ~20/30sec.)
     */
    static public function Parcours_Max_Time() {
        return 0;
    }
    /**
     * Calcul "Au plus proche", définit le rayon max de recherche autour du point de départ/arrivé
     * Ne pas mettre trop haut. Ça n'aurait plus d'intérêts, et alourdirait plus qu'autre chose
     * @return integer default: 5
     */
    static public function Parcours_Nearest() {
        return 5;
    }

    /**
     *  Redéfinition des niveau d'accès par défaut. (optionel)
     */
    static function Perms() {
        // filtre pour cleaning...
        // if \(!defined\('.*'\)\)\s*

        // define('CXX_CARTOGRAPHIE', AXX_MEMBER);
        // define('CXX_CARTOGRAPHIE_SEARCH', AXX_MEMBER);

        // define('CXX_CARTE', AXX_MEMBER);
        // define('CXX_CARTE_JOUEUR', AXX_MEMBER);
        // define('CXX_CARTE_SHOWEMPIRE', AXX_MEMBER);
        // define('CXX_CARTE_SEARCH', AXX_MEMBER);

        // define('CXX_PERSO', AXX_MEMBER);
        // Désactivé, pas besoin de compte pour cette page. voir 'recherche.php' pour ré-activer.
        // define('CXX_PERSO_RESEARCH', AXX_GUEST);
        // define('CXX_PERSO_OWNUNIVERSE', AXX_MEMBER);


        // define('CXX_MEMBRES_HIERARCHIE', AXX_MEMBER);
        // define('CXX_MEMBRES_NEW', AXX_ADMIN); // inclus les grades...
        // define('CXX_MEMBRES_EDIT', AXX_ADMIN);
        // define('CXX_MEMBRES_STATS', AXX_MEMBER);
        // define('CXX_MEMBRES_NEWPASS', AXX_ROOTADMIN);
        // define('CXX_MEMBRES_DELETE', AXX_ROOTADMIN);
        // define('CXX_MEMBRES_ADMIN', AXX_ROOTADMIN);

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
        return 'australis.fr';
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