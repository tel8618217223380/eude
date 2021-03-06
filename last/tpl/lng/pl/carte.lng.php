<?php
/**
 * @author Alex10336
 * @translator Jhonny, Cthulhu
 * Dernière modification: $Id$
 * @license GNU Public License 3.0 ( http://www.gnu.org/licenses/gpl-3.0.txt )
 * @license Creative Commons 3.0 BY-SA ( http://creativecommons.org/licenses/by-sa/3.0/deed.fr )
 *
 **/

$lng = array();

// Bulles d'information 'Carte.php'
$lng['helpmsg'] = <<<MSG
            <b>Uzywanie Mapy:</b><br>
			Kliknij LPM: wybierz punkt poczatkowy<br>
			Kliknij RPM: wybierz punkt koncowy<br>
			Shift/Ctrl + Kliknij: Pokarz dokladnosc systemu
MSG;
$lng['msg_taill_inc'] = 'Wielkosc Mapy +';
$lng['msg_taill_dec'] = 'Wielkosc Mapy &#151;';
$lng['msg_cls']       = 'Kolory Mapy';
$lng['msg_all_on']    = 'Aktywuj wszystko';
$lng['msg_all_off']   = 'Wylacz wszystko';
$lng['msg_vortex']    = 'Wormhole: %s';
$lng['msg_joueur']    = 'Gracze: %s';
$lng['msg_planete']   = 'Planety: %s';
$lng['msg_asteroide'] = 'Asteroidy: %s';
$lng['msg_ennemis']   = 'Wrogowie: %s';
$lng['msg_allys']     = 'Sojusznicy: %s';
$lng['msg_pirate']    = 'Floty Pirackie: %s';
$lng['msg_coords']    = 'Coordonnée';
$lng['msg_search1']   = 'Wybierz Imperium lub Gracza'; // 'Choix empire/joueur/planète/flotte'
$lng['msg_search2']   = 'Enter:Wyszukaj';
$lng['msg_search_emp']= 'Imperium';
$lng['msg_search_jou']= 'Gracz';
$lng['msg_search_info']= 'Nom de planète/flotte';

// Menu pour le calcul
$lng['parcours_header']= 'Navigator';
$lng['parcours_select']= 'Trasa';
$lng['parcours_option']= '[Wybierz Trase]';
$lng['parcours_msg_load']= 'Charger parcours';
$lng['parcours_msg_save']= 'Enregistrer parcours';
$lng['parcours_msg_del']= 'Supprimer parcours';
$lng['parcours_msg_inv']= 'Intervertir les coords';
$lng['parcours_start_ss']= 'System Poczatkowy';
$lng['parcours_end_ss']= 'System Koncowy';
$lng['parcours_old_wormhole']= 'Uzywac skoki "Nie Aktywne"';
$lng['parcours_nointrass']= 'Pas de déplacement par impulsion';
$lng['parcours_method_1']= '1 skok max (Szybkie Licz.))';
$lng['parcours_method_2']= '2 skoky max (Normalne)';
$lng['parcours_method_3']= '3 skoky max ([Bardzo Wolne Licz.))';
$lng['parcours_method_10']= 'Jak najblizej (Szybkie))';
$lng['parcours_start']= 'Start';
$lng['parcours_bywormhole']= 'skok %s';
$lng['parcours_end']= 'Koniec';
$lng['parcours_diff']= 'Roznica';

// Bulles sur la carte...
// Ne pas utiliser de guillemets
$lng['map_ownplanet']= '<b>Votre planète: %s</b>'; // planet name
$lng['map_empire_header']= '<b>%d Membre(s) %s</b>'; // number / empire
$lng['map_alliance_header']= '<b>%d Membre(s) d\'une alliance/pna</b>'; // number
$lng['map_search_header']= '<b>Recherche: %d résultat(s):</b>'; // number
$lng['map_player_header']= '<b> %d Joueur(s)</b>'; // number
$lng['map_ennemy_header']= '<b> %d Ennemi(s)</b>'; // number
$lng['map_pnj_header']= '<b> %d Flotte(s) pirate</b>'; // number
$lng['map_wormhole_header']= '<b> %d Vortex</b>'; // number
$lng['map_planet_header']= '<b> %d Planète(s)</b>'; // number
$lng['map_asteroid_header']= '<b> %d Astéroïde(s)</b>'; // number

$lng['map_row_player1']= '%s (%s)'; // player / eude grade
$lng['map_row_player2']= '%s <i>(non inscrit)</i>'; // player
$lng['map_row_player3']= '%s (%s)'; // player / empire
$lng['map_row_player4']= '<font color=red>%s</font> (%s)'; // player(ennemy) / empire

$lng['map_parcours_start']= 'Départ imminent';
$lng['map_parcours_end']= 'Vous êtes arrivé';
$lng['map_parcours_wormhole']= 'Itinéraire (vortex)';
$lng['map_parcours']= '<font color=\'darkgreen\'><b>%s</b></font>';


// Légende affiché sur la page 'Carte.php'
$lng['legend'] = 'Legenda';
$lng['maplegend']        = array ();
// Mode calcul de parcours
$lng['maplegend'][0]     = array();
//$lng['maplegend'][0][0]  = 'Portée du radar';
//$lng['maplegend'][0][1]  = 'Astre quelconque';
$lng['maplegend'][0][2]  = 'Moje kolonie';
//$lng['maplegend'][0][3]  = 'N/A';
//$lng['maplegend'][0][4]  = 'N/A';
//$lng['maplegend'][0][5]  = 'N/A';
//$lng['maplegend'][0][6]  = 'N/A';
//$lng['maplegend'][0][7]  = 'N/A';
//$lng['maplegend'][0][11] = 'N/A';
//$lng['maplegend'][0][8]  = 'N/A';
//$lng['maplegend'][0][9]  = 'N/A';
//$lng['maplegend'][0][10] = 'N/A';
$lng['maplegend'][0][20] = 'Départ...';
$lng['maplegend'][0][21] = 'Arrivée.';
$lng['maplegend'][0][22] = 'Passage par vortex.';
//$lng['maplegend'][0][24] = 'Navigation \'Warp\' normale';
//$lng['maplegend'][0][25] = 'Navigation par vortex.';

// Palette de couleur 1
$lng['maplegend'][1]     = array();
//$lng['maplegend'][1][0]  = 'Portée du radar';
$lng['maplegend'][1][1]  = 'Gracze Imperium';
$lng['maplegend'][1][2]  = 'Moje kolonie';
$lng['maplegend'][1][3]  = 'Neutralni Gracze';
$lng['maplegend'][1][4]  = 'Wormhole';
$lng['maplegend'][1][5]  = 'Asteroidy';
//$lng['maplegend'][1][6]  = 'Planètes vide / Autre';
//$lng['maplegend'][1][7]  = 'Joueur de l\'empire + autres';
$lng['maplegend'][1][11] = 'Sojusznicy';
$lng['maplegend'][1][8]  = 'Wrogowie';
$lng['maplegend'][1][9]  = 'Automatyczny Pilot';
$lng['maplegend'][1][10] = 'Cel Podrozy';
//$lng['maplegend'][1][20] = 'N/A';
//$lng['maplegend'][1][21] = 'N/A';
//$lng['maplegend'][1][22] = 'N/A';
//$lng['maplegend'][1][24] = 'N/A';
//$lng['maplegend'][1][25] = 'N/A';

// Palette de couleur 2 (couleurs de base )
$lng['maplegend'][2]     = array();
//$lng['maplegend'][2][0]  = 'Portée du radar';
$lng['maplegend'][2][1]  = 'Gracze Imperium';
$lng['maplegend'][2][2]  = 'Moje kolonie';
$lng['maplegend'][2][3]  = 'Astre quelconque';
//$lng['maplegend'][2][4]  = 'Vortex';
//$lng['maplegend'][2][5]  = 'Astéroïdes';
//$lng['maplegend'][2][6]  = 'Planètes vide / Autre';
//$lng['maplegend'][2][7]  = 'Joueur de l\'empire + autres';
//$lng['maplegend'][2][11] = 'Alliés';
//$lng['maplegend'][2][8]  = 'Joueurs ennemi';
//$lng['maplegend'][2][9]  = 'Flottes PNJ';
$lng['maplegend'][2][10] = 'Cel Podrozy';
//$lng['maplegend'][2][20] = 'N/A';
//$lng['maplegend'][2][21] = 'N/A';
//$lng['maplegend'][2][22] = 'N/A';
//$lng['maplegend'][2][24] = 'N/A';
//$lng['maplegend'][2][25] = 'N/A';
