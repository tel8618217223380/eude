//
// DO NO MODIFY DIRECTLY !!!
//
var metadata = <><![CDATA[
// ==UserScript==
// @author       Alex10336
// @name         Data Engine
// @namespace    http://eude.googlecode.com/
// @version      1.4.5
// @lastmod      $Id$
// @license      GNU Public License 3.0 ( http://www.gnu.org/licenses/gpl-3.0.txt )
// @license      Creative Commons 3.0 BY-SA ( http://creativecommons.org/licenses/by-sa/3.0/deed.fr )
// @description  Script de liaison entre firefox et un serveur Data Engine
// @include      http://*eu2.looki.tld/index.php
// @include      http://*eu2.looki.tld/galaxy/galaxy_overview.php*
// @include      http://*eu2.looki.tld/galaxy/galaxy_info.php*
// @include      http://*eu2.looki.tld/planet/planet_info.php*
// @include      http://*eu2.looki.tld/fleet/fleet_info.php*
// @include      http://*eu2.looki.tld/fleet/fleet_edit.php*
// @include      http://*eu2.looki.tld/fleet/fleet_troop.php*
// @include      http://*eu2.looki.tld/fleet/commander_info.php?commander_id=*
// @include      http://*eu2.looki.tld/wormhole/wormhole_info.php*
// @include      http://*eu2.looki.tld/building/control/control_overview.php?area=planet
// @include      http://*eu2.looki.tld/user/settings_overview.php?area=options
// @include      http://*eu2.looki.tld/battle/battle_ground_report_info.php?area=ground_battle*
// @include      http://*eu2.looki.tld/gamelog/gamelog_view.php?gamelog_id*
// @include      http://*eu2.looki.tld/empire/empire_info.php?area=member&empire_id=*
// @include      http://*eu2.looki.tld/empire/empire_info.php?empire_id=*
// @include      http://*eu2.looki.tld/empire/empire_info.php?area=info&empire_id=*
// @include      http://*eu2.looki.tld/empire/empire_info.php?user_id=*&empire_id=*
// @include      http://marketing.looki-france.net/*pub_jeux_*
// @exclude      http://vs.eu2.looki.tld/*
// ==/UserScript==
]]></>.toString();

var c_url = document.location.href;
var c_host = document.location.hostname;
var c_server = c_host.substr(0, c_host.indexOf('.'));
var c_lang = c_host.substr(-3);
c_lang = c_lang.substr(c_lang.indexOf('.')+1);
var c_page = c_url.substr(7+c_host.length);
var c_prefix = c_server+'.'+c_lang;
if (c_prefix == 'eu2.fr') c_prefix = 'australis.fr';
metadata.search(/\@version\s+(.*)/);
var mversion=RegExp.$1.replace(/\.*/g, '');
metadata.search(/Id\:\ eude\.user\.js\ (\d+)\ \d+\-\d+\-\d+\ .+\$/);
var revision=RegExp.$1;
var version=mversion+'r'+revision;
const debug=true;
//c_prefix = 'borealis.fr';

//const UseTamper = function_exists('TM_log');
//
//if (UseTamper) {
//    TM_log('Version '+version);
//    TM_log('Page Check '+c_page);
//}

try {
    var c_game_lang = (typeof unsafeWindow.top.window.fv['lang'] != 'undefined') ? unsafeWindow.top.window.fv['lang']: c_lang;
} catch(e) {
    c_game_lang = c_lang;
}

//if (UseTamper) TM_log('Check Point, should no work after yet !');


if (c_page.indexOf('pub_jeux_')>0 && debug &&
    c_page.indexOf('?')==-1 &&
    c_host == 'marketing.looki-france.net') {
    // html/body/form/table/tbody/tr/input
    try {
        main.document.forms['compteur'].nbClick.value = '2';
    } catch(e) {
        unsafeWindow.main.document.forms['compteur'].nbClick.value = '2';
    }
return true; // stop script...
}

var i18n = Array();
i18n['fr'] = Array();
i18n['fr']['eudeready']      = '<u>Data Engine</u> Français, actif';
i18n['fr']['confheader']     = 'Options spécifique au <u>Data Engine</u>';
i18n['fr']['conflink']       = 'Adresse';
i18n['fr']['confuser']       = 'Nom d\'utilisateur';
i18n['fr']['confpass']       = 'Mot de passe';
i18n['fr']['confspacer']     = 130;
//i18n['fr']['confcells']      = 20;
i18n['fr']['coords']         = 'Coordonnées';
i18n['fr']['ress0']          = 'Titane';
i18n['fr']['ress1']          = 'Cuivre';
i18n['fr']['ress2']          = 'Fer';
i18n['fr']['ress3']          = 'Aluminium';
i18n['fr']['ress4']          = 'Mercure';
i18n['fr']['ress5']          = 'Silicium';
i18n['fr']['ress6']          = 'Uranium';
i18n['fr']['ress7']          = 'Krypton';
i18n['fr']['ress8']          = 'Azote';
i18n['fr']['ress9']          = 'Hydrogène';
i18n['fr']['ss_preview']     = 'Aperçu système solaire n°';
i18n['fr']['neutral,planet'] = ' planètes';
i18n['fr']['emp,planet']     = ' joueur(s) de l\'empire';
i18n['fr']['ally,planet']    = ' joueur(s) allié';
i18n['fr']['war,planet']     = ' joueur(s) en guerre';
i18n['fr']['nap,planet']     = ' joueur(s) en pna';
i18n['fr']['wormhole']       = ' vortex';
i18n['fr'][',asteroid']      = ' astéroïde(s)';
i18n['fr'][',wreckage']      = ' champs de débris';
i18n['fr']['neutral,fleet']  = ' flotte(s) neutre';
i18n['fr']['own,fleet']      = ' flotte(s) perso';
i18n['fr']['nap,fleet']      = ' flotte(s) en pna';
i18n['fr']['enemy,fleet']    = ' flotte(s) ennemie(s)';
i18n['fr']['npc,fleet']      = ' flotte(s) pirate';
i18n['fr']['ga,fleet']       = ' flotte(s) schtroumpfs';
i18n['fr']['troop_log_def']  = 'Dévalisé par';
i18n['fr']['troop_log_att']  = 'Quitter la planète';
// TODO: Replace by XPath...
i18n['fr']['building']       = 'Nombre de bâtiments';
i18n['fr']['water']          = 'Surface d\'eau';
i18n['fr']['active_empire']  = 'Activer MAJ empire';

if (c_game_lang == 'com') c_game_lang = 'en';
i18n['en'] = Array();
i18n['en']['eudeready']      = 'English <u>Data Engine</u> online';
i18n['en']['confheader']     = '<u>Data Engine</u> specifics options';
i18n['en']['conflink']       = 'Address';
i18n['en']['confuser']       = 'User name';
i18n['en']['confpass']       = 'Password';
i18n['en']['confspacer']     = 65;
//i18n['en']['confcells']      = 20;
i18n['en']['coords']         = 'Coordinates';
i18n['en']['ress0']          = 'Titanium';
i18n['en']['ress1']          = 'Copper';
i18n['en']['ress2']          = 'Iron';
i18n['en']['ress3']          = 'Aluminium';
i18n['en']['ress4']          = 'Mercury';
i18n['en']['ress5']          = 'Silicon';
i18n['en']['ress6']          = 'Uranium';
i18n['en']['ress7']          = 'Krypton';
i18n['en']['ress8']          = 'Nitrogen';
i18n['en']['ress9']          = 'Hydrogen';
i18n['en']['ss_preview']     = 'Starsystem overview n°';
i18n['en']['neutral,planet'] = ' Planet';
i18n['en']['emp,planet']     = ' Empire Planet';
i18n['en']['ally,planet']    = ' Alliance Planet';
i18n['en']['war,planet']     = ' enemy Planet';
i18n['en']['nap,planet']     = ' Nap Planet';
i18n['en']['wormhole']       = ' Wormhole';
i18n['en'][',asteroid']      = ' Asterroide';
i18n['en'][',wreckage']      = ' Wreckage';
i18n['en']['neutral,fleet']  = ' Neutral Fleet';
i18n['en']['own,fleet']      = ' Empire/Alliance Fleet';
i18n['en']['nap,fleet']      = ' Nap Fleet';
i18n['en']['enemy,fleet']    = ' Enemy Fleet';
i18n['en']['npc,fleet']      = ' Reaper Fleet';
i18n['en']['ga,fleet']       = ' Passive Fleet';
i18n['en']['troop_log_def']  = 'Robbed by';
i18n['en']['troop_log_att']  = 'Leave planet';
i18n['en']['building']       = 'Amount of buildings';
i18n['en']['water']          = 'Water surface';
i18n['en']['active_empire']  = 'Activate empire update';

i18n['de'] = Array();
i18n['de']['eudeready']      = '<u>Data Engine</u> "de", actif';
i18n['de']['confheader']     = 'Options spécifique au <u>Data Engine</u>';
i18n['de']['conflink']       = 'Adresse';
i18n['de']['confuser']       = 'Nickname';
i18n['de']['confpass']       = 'Passwort';
i18n['de']['confspacer']     = 1;
//i18n['de']['confcells']      = 20;
i18n['de']['coords']         = 'Koordinaten';
i18n['de']['ress0']          = 'Titan';
i18n['de']['ress1']          = 'Kupfer';
i18n['de']['ress2']          = 'Eisen';
i18n['de']['ress3']          = 'Aluminium';
i18n['de']['ress4']          = 'Quecksilber';
i18n['de']['ress5']          = 'Silizium';
i18n['de']['ress6']          = 'Uran';
i18n['de']['ress7']          = 'Krypton';
i18n['de']['ress8']          = 'Stickstoff';
i18n['de']['ress9']          = 'Wasserstoff';
i18n['de']['ss_preview']     = 'Aperçu système solaire n°';
i18n['de']['neutral,planet'] = ' planètes';
i18n['de']['emp,planet']     = ' joueur(s) de l\'empire';
i18n['de']['ally,planet']    = ' joueur(s) allié';
i18n['de']['war,planet']     = ' joueur(s) en guerre';
i18n['de']['nap,planet']     = ' joueur(s) en pna';
i18n['de']['wormhole']       = ' vortex';
i18n['de'][',asteroid']      = ' astéroïde(s)';
i18n['de'][',wreckage']      = ' champs de débris';
i18n['de']['neutral,fleet']  = ' flotte(s) neutre';
i18n['de']['own,fleet']      = ' flotte(s) perso';
i18n['de']['nap,fleet']      = ' flotte(s) en pna';
i18n['de']['enemy,fleet']    = ' flotte(s) ennemie(s)';
i18n['de']['npc,fleet']      = ' flotte(s) pirate';
i18n['de']['ga,fleet']       = ' flotte(s) schtroumpfs';
i18n['de']['troop_log_def']  = 'Dévalisé par';
i18n['de']['troop_log_att']  = 'Quitter la planète';
i18n['de']['building']       = 'Nombre de bâtiments';
i18n['de']['water']          = 'Surface d\'eau';
i18n['de']['active_empire']  = 'Activer MAJ empire';

// [PL] translation by jhonny
i18n['pl'] = Array();
i18n['pl']['eudeready']      = '<u>Data Engine</u> "pl", actif';
i18n['pl']['confheader']     = 'Opcje ustawienia do <u>Data Engine</u>';
i18n['pl']['conflink']       = 'Strona';
i18n['pl']['confuser']       = 'Użytkownik';
i18n['pl']['confpass']       = 'Hasło';
i18n['pl']['confspacer']     = 1;
//i18n['pl']['confcells']      = 20;
i18n['pl']['coords']         = 'Współrzędne';
i18n['pl']['ress0']          = 'Tytan';
i18n['pl']['ress1']          = 'Miedź';
i18n['pl']['ress2']          = 'Żelazo';
i18n['pl']['ress3']          = 'Aluminium';
i18n['pl']['ress4']          = 'Rtęć';
i18n['pl']['ress5']          = 'Krzem';
i18n['pl']['ress6']          = 'Uran';
i18n['pl']['ress7']          = 'Krypton';
i18n['pl']['ress8']          = 'Azot';
i18n['pl']['ress9']          = 'Wodór';
i18n['pl']['ss_preview']     = 'Przegląd systemu gwiezdnego n°';
i18n['pl']['neutral,planet'] = ' Planety';
i18n['pl']['emp,planet']     = ' Członkowie imperium';
i18n['pl']['ally,planet']    = ' Członkowie sojuszu';
i18n['pl']['war,planet']     = ' Członkowie Wrogowie';
i18n['pl']['nap,planet']     = ' Członkowie PON';
i18n['pl']['wormhole']       = ' Wormhold';
i18n['pl'][',asteroid']      = ' Asteroidy';
i18n['pl'][',wreckage']      = ' Złom';
i18n['pl']['neutral,fleet']  = ' Neutralne Floty';
i18n['pl']['own,fleet']      = ' Moje Floty';
i18n['pl']['nap,fleet']      = ' Floty PON';
i18n['pl']['enemy,fleet']    = ' Wrogie Floty';
i18n['pl']['npc,fleet']      = ' Pirackie Floty';
i18n['pl']['ga,fleet']       = ' Smerfy Floty';
i18n['pl']['troop_log_def']  = 'Dévalisé par';
i18n['pl']['troop_log_att']  = 'Quitter la planète';
i18n['pl']['building']       = 'Nombre de bâtiments';
i18n['pl']['water']          = 'Surface d\'eau';
i18n['pl']['active_empire']  = 'Activer MAJ empire';

function $() {
    if (arguments.length==1) return document.getElementById(arguments[0]);
    var z=[], i=0, el;
    while(el=document.getElementById(arguments[i++]))
        if (el)
            z.push(el);
    return z;
}

function $x() {
    var x='',          // default values
    node=document,
    type=0,
    fix=true,
    i=0,
    toAr=function(xp){      // XPathResult to array
        var _final=[], next;
        while(next=xp.iterateNext(),next)
            _final.push(next);
        return _final
    },
    cur;
    while (cur=arguments[i++],cur)      // argument handler
        switch(typeof cur) {
            case "string":
                x+=(x=='') ? cur : " | " + cur;
                continue;
            case "number":
                type=cur;
                continue;
            case "object":
                node=cur;
                continue;
            case "boolean":
                fix=cur;
                continue;
        }
    if (fix) {      // array conversion logic
        if (type==6) type=4;
        if (type==7) type=5;
    }
    if (!/^\//.test(x)) x="//"+x;         	 // selection mistake helper
    if (node!=document && !/^\./.test(x)) x="."+x;  // context mistake helper
    var temp=document.evaluate(x,node,null,type,null); //evaluate!
    if (fix)
        switch(type) {                              // automatically return special type
            case 1:
                return temp.numberValue;
            case 2:
                return temp.stringValue;
            case 3:
                return temp.booleanValue;
            case 8:
                return temp.singleNodeValue;
            case 9:
                return temp.singleNodeValue;
        }
    return fix ? toAr(temp) : temp;
}

function trim (text) {
    return rtrim(ltrim(text));
}
/*
 * More info at: http://phpjs.org
 *
 * This is version: 3.17
 * php.js is copyright 2010 Kevin van Zonneveld.
 *
 * Portions copyright Brett Zamir (http://brett-zamir.me), Kevin van Zonneveld
 * (http://kevin.vanzonneveld.net), Onno Marsman, Theriault, Michael White
 * (http://getsprink.com), Waldo Malqui Silva, Paulo Freitas, Jonas Raoni
 * Soares Silva (http://www.jsfromhell.com), Jack, Philip Peterson, Legaev
 * Andrey, Ates Goral (http://magnetiq.com), Alex, Ratheous, Martijn Wieringa,
 * lmeyrick (https://sourceforge.net/projects/bcmath-js/), Nate, Philippe
 * Baumann, Enrique Gonzalez, Webtoolkit.info (http://www.webtoolkit.info/),
 * Jani Hartikainen, Ash Searle (http://hexmen.com/blog/), travc, Ole
 * Vrijenhoek, Carlos R. L. Rodrigues (http://www.jsfromhell.com),
 * http://stackoverflow.com/questions/57803/how-to-convert-decimal-to-hex-in-javascript,
 * Michael Grier, Johnny Mast (http://www.phpvrouwen.nl), stag019, Rafał
 * Kukawski (http://blog.kukawski.pl), pilus, T.Wild, Andrea Giammarchi
 * (http://webreflection.blogspot.com), WebDevHobo
 * (http://webdevhobo.blogspot.com/), GeekFG (http://geekfg.blogspot.com),
 * d3x, Erkekjetter, marrtins, Steve Hilder, Martin
 * (http://www.erlenwiese.de/), Robin, Oleg Eremeev, mdsjack
 * (http://www.mdsjack.bo.it), majak, Mailfaker (http://www.weedem.fr/),
 * David, felix, Mirek Slugen, KELAN, Paul Smith, Marc Palau, Chris, Josh
 * Fraser
 * (http://onlineaspect.com/2007/06/08/auto-detect-a-time-zone-with-javascript/),
 * Breaking Par Consulting Inc
 * (http://www.breakingpar.com/bkp/home.nsf/0/87256B280015193F87256CFB006C45F7),
 * Tim de Koning (http://www.kingsquare.nl), Arpad Ray (mailto:arpad@php.net),
 * Public Domain (http://www.json.org/json2.js), Michael White, Steven
 * Levithan (http://blog.stevenlevithan.com), Joris, gettimeofday, Sakimori,
 * Alfonso Jimenez (http://www.alfonsojimenez.com), Aman Gupta, Caio Ariede
 * (http://caioariede.com), AJ, Diplom@t (http://difane.com/), saulius,
 * Pellentesque Malesuada, Thunder.m, Tyler Akins (http://rumkin.com), Felix
 * Geisendoerfer (http://www.debuggable.com/felix), gorthaur, Imgen Tata
 * (http://www.myipdf.com/), Karol Kowalski, Kankrelune
 * (http://www.webfaktory.info/), Lars Fischer, Subhasis Deb, josh, Frank
 * Forte, Douglas Crockford (http://javascript.crockford.com), Adam Wallner
 * (http://web2.bitbaro.hu/), Marco, paulo kuong, madipta, Gilbert, duncan,
 * ger, mktime, Oskar Larsson Högfeldt (http://oskar-lh.name/), Arno, Nathan,
 * Mateusz "loonquawl" Zalega, ReverseSyntax, Francois, Scott Cariss, Slawomir
 * Kaniecki, Denny Wardhana, sankai, 0m3r, noname, john
 * (http://www.jd-tech.net), Nick Kolosov (http://sammy.ru), Sanjoy Roy,
 * Shingo, nobbler, Fox, marc andreu, T. Wild, class_exists, Jon Hohle,
 * Pyerre, JT, Thiago Mata (http://thiagomata.blog.com), Linuxworld, Ozh,
 * nord_ua, lmeyrick (https://sourceforge.net/projects/bcmath-js/this.),
 * Thomas Beaucourt (http://www.webapp.fr), David Randall, merabi, T0bsn,
 * Soren Hansen, Peter-Paul Koch (http://www.quirksmode.org/js/beat.html),
 * MeEtc (http://yass.meetcweb.com), Bryan Elliott, Tim Wiel, Brad Touesnard,
 * XoraX (http://www.xorax.info), djmix, Hyam Singer
 * (http://www.impact-computing.com/), Paul, J A R, kenneth, Raphael (Ao
 * RUDLER), David James, Steve Clay, Ole Vrijenhoek (http://www.nervous.nl/),
 * Marc Jansen, Francesco, Der Simon (http://innerdom.sourceforge.net/), echo
 * is bad, Lincoln Ramsay, Eugene Bulkin (http://doubleaw.com/), JB, Bayron
 * Guevara, Stoyan Kyosev (http://www.svest.org/), LH, Matt Bradley, date,
 * Kristof Coomans (SCK-CEN Belgian Nucleair Research Centre), Pierre-Luc
 * Paour, Martin Pool, Brant Messenger (http://www.brantmessenger.com/), Kirk
 * Strobeck, Saulo Vallory, Christoph, Wagner B. Soares, Artur Tchernychev,
 * Valentina De Rosa, Jason Wong (http://carrot.org/), Daniel Esteban,
 * strftime, Rick Waldron, Mick@el, Anton Ongson, Simon Willison
 * (http://simonwillison.net), Gabriel Paderni, Philipp Lenssen, Marco van
 * Oort, Bug?, Blues (http://tech.bluesmoon.info/), Tomasz Wesolowski, rezna,
 * Eric Nagel, Bobby Drake, Luke Godfrey, Pul, uestla, Alan C, Zahlii, Ulrich,
 * Yves Sucaet, hitwork, sowberry, johnrembo, Brian Tafoya
 * (http://www.premasolutions.com/), Nick Callen, Steven Levithan
 * (stevenlevithan.com), ejsanders, Scott Baker, Philippe Jausions
 * (http://pear.php.net/user/jausions), Aidan Lister
 * (http://aidanlister.com/), Norman "zEh" Fuchs, Rob, HKM, ChaosNo1, metjay,
 * strcasecmp, strcmp, Taras Bogach, jpfle, Alexander Ermolaev
 * (http://snippets.dzone.com/user/AlexanderErmolaev), DxGx, kilops, Orlando,
 * dptr1988, Le Torbi, Pedro Tainha (http://www.pedrotainha.com), James,
 * penutbutterjelly, Christian Doebler, baris ozdil, Greg Frazier, Tod
 * Gentille, Alexander M Beedie, Ryan W Tenney (http://ryan.10e.us),
 * FGFEmperor, gabriel paderni, Atli Þór, Maximusya, daniel airton wermann
 * (http://wermann.com.br), 3D-GRAF, Yannoo, jakes, Riddler
 * (http://www.frontierwebdev.com/), T.J. Leahy, stensi, Matteo, Billy, vlado
 * houba, Itsacon (http://www.itsacon.net/), Jalal Berrami, Victor, fearphage
 * (http://http/my.opera.com/fearphage/), Luis Salazar
 * (http://www.freaky-media.com/), FremyCompany, Tim de Koning, taith, Cord,
 * Manish, davook, Benjamin Lupton, Garagoth, Andrej Pavlovic, Dino, William,
 * rem, Russell Walker (http://www.nbill.co.uk/), Jamie Beck
 * (http://www.terabit.ca/), setcookie, Michael, YUI Library:
 * http://developer.yahoo.com/yui/docs/YAHOO.util.DateLocale.html, Blues at
 * http://hacks.bluesmoon.info/strftime/strftime.js, DtTvB
 * (http://dt.in.th/2008-09-16.string-length-in-bytes.html), Andreas, meo,
 * Greenseed, Luke Smith (http://lucassmith.name), Kheang Hok Chin
 * (http://www.distantia.ca/), Rival, Diogo Resende, Allan Jensen
 * (http://www.winternet.no), Howard Yeend, Jay Klehr, Amir Habibi
 * (http://www.residence-mixte.com/), mk.keck, Yen-Wei Liu, Leslie Hoare, Ben
 * Bryan, Cagri Ekin, booeyOH
 *
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL KEVIN VAN ZONNEVELD BE LIABLE FOR ANY CLAIM, DAMAGES
 * OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

function ltrim ( str, charlist ) {
    // Strips whitespace from the beginning of a string
    //
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/ltrim
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Erkekjetter
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // *     example 1: ltrim('    Kevin van Zonneveld    ');
    // *     returns 1: 'Kevin van Zonneveld    '
    charlist = !charlist ? ' \\s\u00A0' : (charlist+'').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
    var re = new RegExp('^[' + charlist + ']+', 'g');
    return (str+'').replace(re, '');
}

function md5 (str) {
    // Calculate the md5 hash of a string
    //
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/md5
    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // + namespaced by: Michael White (http://getsprink.com)
    // +    tweaked by: Jack
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: utf8_encode
    // *     example 1: md5('Kevin van Zonneveld');
    // *     returns 1: '6e658d4bfcb59cc13f96c14450ac40b9'
    var xl;

    var rotateLeft = function (lValue, iShiftBits) {
        return (lValue<<iShiftBits) | (lValue>>>(32-iShiftBits));
    };

    var addUnsigned = function (lX,lY) {
        var lX4,lY4,lX8,lY8,lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF)+(lY & 0x3FFFFFFF);
        if (lX4 & lY4) {
            return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        }
        if (lX4 | lY4) {
            if (lResult & 0x40000000) {
                return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            } else {
                return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
            }
        } else {
            return (lResult ^ lX8 ^ lY8);
        }
    };

    var _F = function (x,y,z) {
        return (x & y) | ((~x) & z);
    };
    var _G = function (x,y,z) {
        return (x & z) | (y & (~z));
    };
    var _H = function (x,y,z) {
        return (x ^ y ^ z);
    };
    var _I = function (x,y,z) {
        return (y ^ (x | (~z)));
    };

    var _FF = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_F(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _GG = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_G(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _HH = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_H(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _II = function (a,b,c,d,x,s,ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_I(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var convertToWordArray = function (str) {
        var lWordCount;
        var lMessageLength = str.length;
        var lNumberOfWords_temp1=lMessageLength + 8;
        var lNumberOfWords_temp2=(lNumberOfWords_temp1-(lNumberOfWords_temp1 % 64))/64;
        var lNumberOfWords = (lNumberOfWords_temp2+1)*16;
        var lWordArray=new Array(lNumberOfWords-1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while ( lByteCount < lMessageLength ) {
            lWordCount = (lByteCount-(lByteCount % 4))/4;
            lBytePosition = (lByteCount % 4)*8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (str.charCodeAt(lByteCount)<<lBytePosition));
            lByteCount++;
        }
        lWordCount = (lByteCount-(lByteCount % 4))/4;
        lBytePosition = (lByteCount % 4)*8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80<<lBytePosition);
        lWordArray[lNumberOfWords-2] = lMessageLength<<3;
        lWordArray[lNumberOfWords-1] = lMessageLength>>>29;
        return lWordArray;
    };

    var wordToHex = function (lValue) {
        var wordToHexValue="",wordToHexValue_temp="",lByte,lCount;
        for (lCount = 0;lCount<=3;lCount++) {
            lByte = (lValue>>>(lCount*8)) & 255;
            wordToHexValue_temp = "0" + lByte.toString(16);
            wordToHexValue = wordToHexValue + wordToHexValue_temp.substr(wordToHexValue_temp.length-2,2);
        }
        return wordToHexValue;
    };

    var x=[],
    k,AA,BB,CC,DD,a,b,c,d,
    S11=7, S12=12, S13=17, S14=22,
    S21=5, S22=9 , S23=14, S24=20,
    S31=4, S32=11, S33=16, S34=23,
    S41=6, S42=10, S43=15, S44=21;

    str = utf8_encode(str);
    x = convertToWordArray(str);
    a = 0x67452301;
    b = 0xEFCDAB89;
    c = 0x98BADCFE;
    d = 0x10325476;

    xl = x.length;
    for (k=0;k<xl;k+=16) {
        AA=a;
        BB=b;
        CC=c;
        DD=d;
        a=_FF(a,b,c,d,x[k+0], S11,0xD76AA478);
        d=_FF(d,a,b,c,x[k+1], S12,0xE8C7B756);
        c=_FF(c,d,a,b,x[k+2], S13,0x242070DB);
        b=_FF(b,c,d,a,x[k+3], S14,0xC1BDCEEE);
        a=_FF(a,b,c,d,x[k+4], S11,0xF57C0FAF);
        d=_FF(d,a,b,c,x[k+5], S12,0x4787C62A);
        c=_FF(c,d,a,b,x[k+6], S13,0xA8304613);
        b=_FF(b,c,d,a,x[k+7], S14,0xFD469501);
        a=_FF(a,b,c,d,x[k+8], S11,0x698098D8);
        d=_FF(d,a,b,c,x[k+9], S12,0x8B44F7AF);
        c=_FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1);
        b=_FF(b,c,d,a,x[k+11],S14,0x895CD7BE);
        a=_FF(a,b,c,d,x[k+12],S11,0x6B901122);
        d=_FF(d,a,b,c,x[k+13],S12,0xFD987193);
        c=_FF(c,d,a,b,x[k+14],S13,0xA679438E);
        b=_FF(b,c,d,a,x[k+15],S14,0x49B40821);
        a=_GG(a,b,c,d,x[k+1], S21,0xF61E2562);
        d=_GG(d,a,b,c,x[k+6], S22,0xC040B340);
        c=_GG(c,d,a,b,x[k+11],S23,0x265E5A51);
        b=_GG(b,c,d,a,x[k+0], S24,0xE9B6C7AA);
        a=_GG(a,b,c,d,x[k+5], S21,0xD62F105D);
        d=_GG(d,a,b,c,x[k+10],S22,0x2441453);
        c=_GG(c,d,a,b,x[k+15],S23,0xD8A1E681);
        b=_GG(b,c,d,a,x[k+4], S24,0xE7D3FBC8);
        a=_GG(a,b,c,d,x[k+9], S21,0x21E1CDE6);
        d=_GG(d,a,b,c,x[k+14],S22,0xC33707D6);
        c=_GG(c,d,a,b,x[k+3], S23,0xF4D50D87);
        b=_GG(b,c,d,a,x[k+8], S24,0x455A14ED);
        a=_GG(a,b,c,d,x[k+13],S21,0xA9E3E905);
        d=_GG(d,a,b,c,x[k+2], S22,0xFCEFA3F8);
        c=_GG(c,d,a,b,x[k+7], S23,0x676F02D9);
        b=_GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);
        a=_HH(a,b,c,d,x[k+5], S31,0xFFFA3942);
        d=_HH(d,a,b,c,x[k+8], S32,0x8771F681);
        c=_HH(c,d,a,b,x[k+11],S33,0x6D9D6122);
        b=_HH(b,c,d,a,x[k+14],S34,0xFDE5380C);
        a=_HH(a,b,c,d,x[k+1], S31,0xA4BEEA44);
        d=_HH(d,a,b,c,x[k+4], S32,0x4BDECFA9);
        c=_HH(c,d,a,b,x[k+7], S33,0xF6BB4B60);
        b=_HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);
        a=_HH(a,b,c,d,x[k+13],S31,0x289B7EC6);
        d=_HH(d,a,b,c,x[k+0], S32,0xEAA127FA);
        c=_HH(c,d,a,b,x[k+3], S33,0xD4EF3085);
        b=_HH(b,c,d,a,x[k+6], S34,0x4881D05);
        a=_HH(a,b,c,d,x[k+9], S31,0xD9D4D039);
        d=_HH(d,a,b,c,x[k+12],S32,0xE6DB99E5);
        c=_HH(c,d,a,b,x[k+15],S33,0x1FA27CF8);
        b=_HH(b,c,d,a,x[k+2], S34,0xC4AC5665);
        a=_II(a,b,c,d,x[k+0], S41,0xF4292244);
        d=_II(d,a,b,c,x[k+7], S42,0x432AFF97);
        c=_II(c,d,a,b,x[k+14],S43,0xAB9423A7);
        b=_II(b,c,d,a,x[k+5], S44,0xFC93A039);
        a=_II(a,b,c,d,x[k+12],S41,0x655B59C3);
        d=_II(d,a,b,c,x[k+3], S42,0x8F0CCC92);
        c=_II(c,d,a,b,x[k+10],S43,0xFFEFF47D);
        b=_II(b,c,d,a,x[k+1], S44,0x85845DD1);
        a=_II(a,b,c,d,x[k+8], S41,0x6FA87E4F);
        d=_II(d,a,b,c,x[k+15],S42,0xFE2CE6E0);
        c=_II(c,d,a,b,x[k+6], S43,0xA3014314);
        b=_II(b,c,d,a,x[k+13],S44,0x4E0811A1);
        a=_II(a,b,c,d,x[k+4], S41,0xF7537E82);
        d=_II(d,a,b,c,x[k+11],S42,0xBD3AF235);
        c=_II(c,d,a,b,x[k+2], S43,0x2AD7D2BB);
        b=_II(b,c,d,a,x[k+9], S44,0xEB86D391);
        a=addUnsigned(a,AA);
        b=addUnsigned(b,BB);
        c=addUnsigned(c,CC);
        d=addUnsigned(d,DD);
    }

    var temp = wordToHex(a)+wordToHex(b)+wordToHex(c)+wordToHex(d);

    return temp.toLowerCase();
}

function rtrim ( str, charlist ) {
    // Removes trailing whitespace
    //
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/rtrim
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Erkekjetter
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   input by: rem
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: rtrim('    Kevin van Zonneveld    ');
    // *     returns 1: '    Kevin van Zonneveld'
    charlist = !charlist ? ' \\s\u00A0' : (charlist+'').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\\$1');
    var re = new RegExp('[' + charlist + ']+$', 'g');
    return (str+'').replace(re, '');
}

function serialize (mixed_value) {
    // Returns a string representation of variable (which can later be unserialized)
    //
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/serialize
    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // +   improved by: Dino
    // +   bugfixed by: Andrej Pavlovic
    // +   bugfixed by: Garagoth
    // +      input by: DtTvB (http://dt.in.th/2008-09-16.string-length-in-bytes.html)
    // +   bugfixed by: Russell Walker (http://www.nbill.co.uk/)
    // +   bugfixed by: Jamie Beck (http://www.terabit.ca/)
    // +      input by: Martin (http://www.erlenwiese.de/)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: utf8_encode
    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
    var _getType = function (inp) {
        var type = typeof inp, match;
        var key;
        if (type == 'object' && !inp) {
            return 'null';
        }
        if (type == "object") {
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = '';

    switch (type) {
        case "function":
            val = "";
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");
            break;
        case "number":
            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":
            mixed_value = utf8_encode(mixed_value);
            val = "s:" + encodeURIComponent(mixed_value).replace(/%../g, 'x').length + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":
            val = "a";
            /*
            if (type == "object") {
                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {
                    return;
                }
                objname[1] = this.serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);
            }
             */
            var count = 0;
            var vals = "";
            var okey;
            var key;
            for (key in mixed_value) {
                ktype = _getType(mixed_value[key]);
                if (ktype == "function") {
                    continue;
                }

                okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                vals += serialize(okey) +
                serialize(mixed_value[key]);
                count++;
            }
            val += ":" + count + ":{" + vals + "}";
            break;
        case "undefined": // Fall-through
        default: // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
            val = "N";
            break;
    }
    if (type != "object" && type != "array") {
        val += ";";
    }
    return val;
}

function unserialize (data) {
    // Takes a string representation of variable and recreates it
    //
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/unserialize
    // +     original by: Arpad Ray (mailto:arpad@php.net)
    // +     improved by: Pedro Tainha (http://www.pedrotainha.com)
    // +     bugfixed by: dptr1988
    // +      revised by: d3x
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +        input by: Brett Zamir (http://brett-zamir.me)
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: Chris
    // +     improved by: James
    // +        input by: Martin (http://www.erlenwiese.de/)
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: Le Torbi
    // +     input by: kilops
    // +     bugfixed by: Brett Zamir (http://brett-zamir.me)
    // -      depends on: utf8_decode
    // %            note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %            note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *       example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
    // *       returns 1: ['Kevin', 'van', 'Zonneveld']
    // *       example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
    // *       returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}
    var utf8Overhead = function(chr) {
        // http://phpjs.org/functions/unserialize:571#comment_95906
        var code = chr.charCodeAt(0);
        if (code < 0x0080) {
            return 0;
        }
        if (code < 0x0800) {
            return 1;
        }
        return 2;
    };


    var error = function (type, msg, filename, line){
        throw new window[type](msg, filename, line);
    };
    var read_until = function (data, offset, stopchr){
        var buf = [];
        var chr = data.slice(offset, offset + 1);
        var i = 2;
        while (chr != stopchr) {
            if ((i+offset) > data.length) {
                error('Error', 'Invalid');
            }
            buf.push(chr);
            chr = data.slice(offset + (i - 1),offset + i);
            i += 1;
        }
        return [buf.length, buf.join('')];
    };
    var read_chrs = function (data, offset, length){
        var buf;

        buf = [];
        for (var i = 0;i < length;i++){
            var chr = data.slice(offset + (i - 1),offset + i);
            buf.push(chr);
            length -= utf8Overhead(chr);
        }
        return [buf.length, buf.join('')];
    };
    var _unserialize = function (data, offset){
        var readdata;
        var readData;
        var chrs = 0;
        var ccount;
        var stringlength;
        var keyandchrs;
        var keys;

        if (!offset) {
            offset = 0;
        }
        var dtype = (data.slice(offset, offset + 1)).toLowerCase();

        var dataoffset = offset + 2;
        var typeconvert = function(x) {
            return x;
        };

        switch (dtype){
            case 'i':
                typeconvert = function (x) {
                    return parseInt(x, 10);
                };
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
                break;
            case 'b':
                typeconvert = function (x) {
                    return parseInt(x, 10) !== 0;
                };
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
                break;
            case 'd':
                typeconvert = function (x) {
                    return parseFloat(x);
                };
                readData = read_until(data, dataoffset, ';');
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 1;
                break;
            case 'n':
                readdata = null;
                break;
            case 's':
                ccount = read_until(data, dataoffset, ':');
                chrs = ccount[0];
                stringlength = ccount[1];
                dataoffset += chrs + 2;

                readData = read_chrs(data, dataoffset+1, parseInt(stringlength, 10));
                chrs = readData[0];
                readdata = readData[1];
                dataoffset += chrs + 2;
                if (chrs != parseInt(stringlength, 10) && chrs != readdata.length){
                    error('SyntaxError', 'String length mismatch');
                }

                // Length was calculated on an utf-8 encoded string
                // so wait with decoding
                readdata = utf8_decode(readdata);
                break;
            case 'a':
                readdata = {};

                keyandchrs = read_until(data, dataoffset, ':');
                chrs = keyandchrs[0];
                keys = keyandchrs[1];
                dataoffset += chrs + 2;

                for (var i = 0; i < parseInt(keys, 10); i++){
                    var kprops = _unserialize(data, dataoffset);
                    var kchrs = kprops[1];
                    var key = kprops[2];
                    dataoffset += kchrs;

                    var vprops = _unserialize(data, dataoffset);
                    var vchrs = vprops[1];
                    var value = vprops[2];
                    dataoffset += vchrs;

                    readdata[key] = value;
                }

                dataoffset += 1;
                break;
            default:
                error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
                break;
        }
        return [dtype, dataoffset - offset, typeconvert(readdata)];
    };

    return _unserialize((data+''), 0)[2];
}

function utf8_decode ( str_data ) {
    // Converts a UTF-8 encoded string to ISO-8859-1
    //
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/utf8_decode
    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +      input by: Aman Gupta
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Norman "zEh" Fuchs
    // +   bugfixed by: hitwork
    // +   bugfixed by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: utf8_decode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'
    var tmp_arr = [], i = 0, ac = 0, c1 = 0, c2 = 0, c3 = 0;

    str_data += '';

    while ( i < str_data.length ) {
        c1 = str_data.charCodeAt(i);
        if (c1 < 128) {
            tmp_arr[ac++] = String.fromCharCode(c1);
            i++;
        } else if ((c1 > 191) && (c1 < 224)) {
            c2 = str_data.charCodeAt(i+1);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
            i += 2;
        } else {
            c2 = str_data.charCodeAt(i+1);
            c3 = str_data.charCodeAt(i+2);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }
    }

    return tmp_arr.join('');
}

function utf8_encode ( argString ) {
    // Encodes an ISO-8859-1 string to UTF-8
    //
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/utf8_encode
    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: sowberry
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman
    // +   improved by: Yves Sucaet
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Ulrich
    // *     example 1: utf8_encode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'
    var string = (argString+''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");

    var utftext = "";
    var start, end;
    var stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.substring(start, end);
            }
            utftext += enc;
            start = end = n+1;
        }
    }

    if (end > start) {
        utftext += string.substring(start, string.length);
    }

    return utftext;
}
function function_exists (function_name) {
    // Checks if the function exists
    //
    // version: 1006.1915
    // discuss at: http://phpjs.org/functions/function_exists
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Steve Clay
    // +   improved by: Legaev Andrey
    // *     example 1: function_exists('isFinite');
    // *     returns 1: true
    if (typeof function_name == 'string'){
        return (typeof this.window[function_name] == 'function');
    } else {
        return (function_name instanceof Function);
    }
}
function getElementsByClass(searchClass,node,tag) {
	var classElements = new Array();
	if ( node == null )
		node = document;
	if ( tag == null )
		tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}
function options_spacer(width) {
    var cell = document.createElement('td');
    if (!width) width='20';
    cell.innerHTML = '<img width="'+width+'" height="1" src="/img/empty.gif"/>';
    return cell;
}
function options_header(text) {
    var cell = document.createElement('td');
    cell.setAttribute('class', 'font_pink_bold');
    cell.setAttribute('colspan', '4');
    cell.innerHTML = text;
    return cell;
}
function options_cell(text, style) {
    var cell = document.createElement('td');
    cell.setAttribute('class', 'font_white');
    if (style) cell.setAttribute('valign', 'top');
    cell.innerHTML = text;
    return cell;
}

function options_text_s(name, value, width, type) {
    if (!type) type = 'text'; else type = 'password';
    result = "<table cellspacing='0' cellpadding='0'><tr>"+
    "<td><img src='http://static.empireuniverse2.de/default/fr/default/input/input_left_blue.gif'></td>"+
    "<td style='background-image:url(http://static.empireuniverse2.de/default/fr/default/input/input_back_blue.gif);'>"+
    "<input type='"+type+"' class='input' maxlength='' style='width:"+width+
    "px;height:18px;background:transparent;padding:2px 0px 0px 0px; font-size:11px;color:#000000;margin:0px;border:0px' "+
    "' id='"+name+"' value='"+value+"' >    </td>"+
    "<td><img src='http://static.empireuniverse2.de/default/fr/default/input/input_right_blue.gif'></td>"+
    "</tr>    </table>";

    return result;
}

function options_button_save(id) {
    return '<img border="0" id="'+id+
    '" onmousedown="image_swap(\''+id+'\',\'http://static.empireuniverse2.de/default/'+c_game_lang+'/default/button/button_1/save/button_down.gif\')"'+
    ' onmouseover="image_swap(\''+id+'\',\'http://static.empireuniverse2.de/default/'+c_game_lang+'/default/button/button_1/save/button_over.gif\')"'+
    ' onmouseup="image_swap(\''+id+'\',\'http://static.empireuniverse2.de/default/'+c_game_lang+'/default/button/button_1/save/button_default.gif\')"'+
    ' onmouseout="image_swap(\''+id+'\',\'http://static.empireuniverse2.de/default/'+c_game_lang+'/default/button/button_1/save/button_default.gif\')"'+
    ' src="http://static.empireuniverse2.de/default/'+c_game_lang+'/default/button/button_1/save/button_default.gif" class="button"/>';
}

function options_checkbox_s(name, active) {
	var status;
	if (!active) status = ''; else status = 'checked';
    result = "<input type='checkbox' class='input' maxlength='' name='"+name+"' id='"+name+"' value='on' "+status+">";

    return result;
}


function AddGameLog(text) {
    var log = null;
    try {
        log = unsafeWindow.top.document.getElementById('gamelog');
    } catch(e) {
        GM_log('AddGameLog Err:'+text);
    }
    if (log != null) log.innerHTML = text+'<br/>'+log.innerHTML;
}

function AddToMotd(text,sep) {
    var chat_motd = null;
    try {
        chat_motd = unsafeWindow.top.document.getElementById('chat_motd');
    } catch(e) {
        GM_log('AddToMotd Err:'+text);
    }
    if (!sep) sep = '<br/>';
    var tmp = text+sep+chat_motd.innerHTML;
    chat_motd.innerHTML = tmp.substr(0,4000);
}
var c_onload = function(e) {

    if (e.status=='404' || e.status=='405') {
        GM_setValue(c_prefix+'actived','0');
        AddGameLog('<span class="gamelog_raid">No answer with Data Engine ('+e.status+')</span>');
        return alert('No link etablished with ours Data Engine\nCheck address');
        top.location.reload(true);
    }
    if (e.status=='500') {
        AddGameLog('<span class="gamelog_raid">Ours server has crached ?</span>');
        return alert('Ours server has crached ?!');
    }

    if (e.responseText.indexOf('<eude>')<0) {
        alert("XML error, disabling 'eude'...\n\n\n\nData Engine send:\n"+e.responseText);
        if (debug) return false;
        GM_setValue(c_prefix+'actived','0');
        return top.location.reload(true);
    }

    //if (debug) alert("Debug...\n"+e.responseXML+e.responseText);

    if (!e.responseXML)
        e.responseXML = new DOMParser().parseFromString(e.responseText, "text/xml");
    //    alert('xx'+ e.responseXML.getDocumentElement());


    if (GetNode(e.responseXML, 'phperror')!='')
        alert("Error found:\n\n"+GetNode(e.responseXML, 'phperror'));

    if (GetNode(e.responseXML, 'logtype'))
        $type = GetNode(e.responseXML, 'logtype');
    else
        $type = ((e.status!='200')? 'raid':'event');

    if (GetNode(e.responseXML, 'log')!='')
        AddGameLog('<span class="gamelog_'+$type+'">'+GetNode(e.responseXML, 'log')+'</span>');

    if (GetNode(e.responseXML, 'alert')!='') alert(GetNode(e.responseXML, 'alert'));
    if (GetNode(e.responseXML, 'script')!='') eval(GetNode(e.responseXML, 'script'));

    if (GetNode(e.responseXML, 'GM_active')!='') {
        var active = GetNode(e.responseXML, 'GM_active');
        GM_setValue(c_prefix+'actived', active);
        if (active=='1') {
            GM_setValue(c_prefix+'galaxy_info',   GetNode(e.responseXML, 'GM_galaxy_info')  =='1'? true:false);
            GM_setValue(c_prefix+'planet_info',   GetNode(e.responseXML, 'GM_planet_info')  =='1'? true:false);
            GM_setValue(c_prefix+'asteroid_info', GetNode(e.responseXML, 'GM_asteroid_info')=='1'? true:false);
            GM_setValue(c_prefix+'pnj_info',      GetNode(e.responseXML, 'GM_pnj_info')     =='1'? true:false);
            GM_setValue(c_prefix+'troops_battle', GetNode(e.responseXML, 'GM_troops_battle')=='1'? true:false);
			GM_setValue(c_prefix+'empire_maj',  GetNode(e.responseXML, 'GM_empire_maj')     =='1'? true:false);
        }
        if (c_page!='/index.php') top.location.reload(true);
    }
    if (GetNode(e.responseXML, 'content')!='')
        AddToMotd(GetNode(e.responseXML, 'content'));

    return true;
}

var c_onerror = function(e) {
    AddGameLog('<span class="gamelog_raid">Server offline ?</span>');
    AddGameLog('<span class="gamelog_raid">Fatal ('+e.status+'): Use in firefox only</span>');
}

function get_xml(key, data) {
    var _server = GM_getValue(c_prefix+'serveur','')+
    'xml/eude.php?act='+key;
    if (debug) _server += '&XDEBUG_SESSION_START=netbeans-xdebug';
    var _data='';

    switch(typeof data) {
        case "string":
            _data = '&data='+encodeURIComponent(data);
            break;
        case "number":
            _data = '&data='+data;
            break;
        case "object":
            for (var item in data) {
                _data+='&'+item+'='+encodeURIComponent(data[item]);
            }
            break;
        case "boolean":
            if (data)
                _data = '&data=1';
            else
                _data = '&data=0';
            break;
    }
    _data = 'user='+encodeURIComponent(GM_getValue(c_prefix+'user',''))+
    '&pass='+encodeURIComponent(md5(GM_getValue(c_prefix+'pass','')))+
    '&svr='+encodeURIComponent(c_prefix)+_data;

    GM_xmlhttpRequest({
        method: 'POST',
        headers: {
            'Content-Type':'application/x-www-form-urlencoded',
            "User-Agent": navigator.userAgent,
            "Accept": "text/xml",
            "Accept-Encoding":"deflate"
        },
        data: _data,
        url: _server,
        onload: c_onload,
        onerror: c_onerror
    });
}

function GetNode (xml, tag){
    try
    {
        var tagdata = xml.firstChild.getElementsByTagName(tag);
        if (tagdata.length>0)
            return tagdata[0].firstChild.nodeValue;
        else
            return '';
    } catch (e) {
        return 'Erreur XML';
    }
    return '';
}
function Index() {
    AddGameLog('<span class="gamelog_event">'+i18n[c_game_lang]['eudeready']+'</span>');
    var script = document.createElement('script');
    script.type = 'text/javascript';
    var tmp = <><![CDATA[
    oldSetTimeout = window.setTimeout;
    window.setTimeout = function(code, interval) {
    if (code=='chatOpen()') {
    window.setTimeout=oldSetTimeout;
    return false;
    }
    oldSetTimeout(code, interval);
    }
    function eude_ShowChat() {
    var chattonmotd = top.window.document.getElementById('chat_motd');
    var chattonswf = top.window.document.getElementById('myContent');
    var chattondiv = top.window.document.getElementById('chat');

    chattonmotd.style.visibility = 'hidden';
    chattonswf.style.visibility = 'visible';
    chattondiv.style.display = '';
    }
    function eude_HideChat() {
    var chattonmotd = top.window.document.getElementById('chat_motd');
    var chattonswf = top.window.document.getElementById('myContent');

    chattonmotd.style.visibility = 'visible';
    chattonswf.style.visibility = 'hidden';
    }
    window.chatOpen = eude_ShowChat;
    ]]></>.toString();

    script.text = tmp;
    $x('/html/body')[0].appendChild(script);

    var aserver = document.createElement('a');
    aserver.href=GM_getValue(c_prefix+'serveur','');
    aserver.target='_blank';
    aserver.innerHTML = 'Data Engine';

    x = $x('//*[@id="linkline"]');
    block = x[x.length-1];
    block.innerHTML = block.innerHTML + ' | ';
    block.appendChild(aserver);
    var chatton = unsafeWindow.document.getElementById('chat_motd');
    chatton.style.height = 500;

    if (debug) {
        chatton.removeAttribute('OnClick');
        var js_OnClick = document.createAttribute('Ondblclick');
        js_OnClick.value = "eude_ShowChat();";
        chatton.setAttributeNode(js_OnClick);
        var adebug = document.createElement('a');
        adebug.href='javascript:;';
        adebug.innerHTML = 'Reset';
        js_OnClick = document.createAttribute('OnClick');
        js_OnClick.value = "eude_HideChat();top.window.document.getElementById('chat_motd').innerHTML='';";
        adebug.setAttributeNode(js_OnClick);
        block.innerHTML = block.innerHTML + ', ';
        block.appendChild(adebug);
    } else {
        chatton.removeAttribute('OnClick');
        var js_OnClick = document.createAttribute('OnClick');
        js_OnClick.value = "eude_ShowChat();";
        chatton.setAttributeNode(js_OnClick);
        var alog = document.createElement('a');
        alog.href='javascript:;';
        alog.innerHTML = 'Log';
        var js_OnClick = document.createAttribute('OnClick');
        js_OnClick.value = "eude_HideChat();";
        alog.setAttributeNode(js_OnClick);
        block.innerHTML = block.innerHTML + ', ';
        block.appendChild(alog);
    }

    get_xml('init');
    if (debug || mversion=='svn' || revision == '') return AddGameLog('<span class="gamelog_raid">Dev release, no update check</span>');

    GM_xmlhttpRequest({
        method: 'GET',
        headers: {
            "User-Agent": navigator.userAgent,
            "Accept": "text/xml",
            "Accept-Encoding":"deflate"
        },
        url: 'http://eude.googlecode.com/svn/tag/GreaseMonkey/lastrelease.xml',
        onload: function(e){
            if (e.status!='200') {
                AddGameLog('<span class="gamelog_raid">Official server has dirty answer (omg)</span>');
                return;
            }

            if (!e.responseXML)
                e.responseXML = new DOMParser().parseFromString(e.responseText, "text/xml");
            rversion = GetNode(e.responseXML, 'rversion');
            eudeversion = GetNode(e.responseXML, 'eudeversion');
            majurl = GetNode(e.responseXML, 'url');
            majlog = GetNode(e.responseXML, 'log');
            if (revision<rversion) {
                AddToMotd('<b>Log:</b><br/>'+majlog, '<hr/>');
                if (mversion==eudeversion)
                    AddToMotd('<a href="'+majurl+'" class="gamelog_raid">=> MAJ Greasemonkey disponible</a>');
                AddToMotd('<hr/>Mise à jour disponible de '+mversion+'r'+revision+' vers '+eudeversion+'r'+rversion);

                if (mversion==eudeversion)
                    AddGameLog('<a href="'+majurl+'" class="gamelog_raid">=> MAJ Greasemonkey</a>');
                else
                    AddGameLog('<a href="'+majurl+'" class="gamelog_raid">Une mise à jour est disponible</a>');
            }
        },
        onerror: c_onerror
    });
}

function Galaxy() {
    var reg=/orb\[\d+\]\='(\w+),[^,]*,[^,]*,[^,]*,[^,]*,(\w+|),[^,]*,[^,]*,([0-9a-h]{32})(?:,(?:&nbsp;|[^;])+)?';/g;
    var m = document.documentElement.innerHTML.match(reg);

    var e=new Array();
    for (i = 0; i < m.length; i++) {
        m[i].search(reg);
        found = RegExp.$2+','+RegExp.$1;
        if (found.substr(1,8)=='wormhole') found='wormhole';
        if (e[found])
            e[found]= e[found]+1;
        else
            e[found]=1;
    }

    var msg = '<br/><b>'+i18n[c_game_lang]['ss_preview']+document.getElementById('target_starsystem_id').value+'</b>';
    if (e['neutral,planet']) msg += '<br/>'+                      e['neutral,planet']+i18n[c_game_lang]['neutral,planet'];
    if (e['emp,planet'])     msg += '<br/><font color=#ffff88>'+  e['emp,planet']    +i18n[c_game_lang]['emp,planet']     +'</font>';
    if (e['ally,planet'])    msg += '<br/><font color=gold>'+     e['ally,planet']   +i18n[c_game_lang]['ally,planet']    +'</font>';
    if (e['war,planet'])     msg += '<br/><font color=red>'+      e['war,planet']    +i18n[c_game_lang]['war,planet']     +'</font>';
    if (e['nap,planet'])     msg += '<br/><font color=#9966FF>'+  e['nap,planet']    +i18n[c_game_lang]['nap,planet']     +'</font>';
    if (e['wormhole'])       msg += '<br/><font color=#AABBFF>'+  e['wormhole']      +i18n[c_game_lang]['wormhole']       +'</font>';
    if (e[',asteroid'])      msg += '<br/><font color=gray>'+     e[',asteroid']     +i18n[c_game_lang][',asteroid']      +'</font>';
    if (e[',wreckage'])      msg += '<br/><font color=#AA55FF>'+  e[',wreckage']     +i18n[c_game_lang][',wreckage']      +'</font>';
    if (e['neutral,fleet'])  msg += '<br/>'+                      e['neutral,fleet'] +i18n[c_game_lang]['neutral,fleet'];
    if (e['own,fleet'])      msg += '<br/><font color=green>'+    e['own,fleet']     +i18n[c_game_lang]['own,fleet']      +'</font>';
    if (e['nap,fleet'])      msg += '<br/><font color=#9966FF>'+  e['nap,fleet']     +i18n[c_game_lang]['nap,fleet']      +'</font>';
    if (e['enemy,fleet'])    msg += '<br/><font color=red>'+      e['enemy,fleet']   +i18n[c_game_lang]['enemy,fleet']    +'</font>';
    if (e['npc,fleet'])      msg += '<br/><font color=gold>'+     e['npc,fleet']     +i18n[c_game_lang]['npc,fleet']      +'</font>';
    if (e['ga,fleet'])       msg += '<br/><font color=lightblue>'+e['ga,fleet']      +i18n[c_game_lang]['ga,fleet']       +'</font>';
    delete(e['neutral,sun']);
    delete(e['neutral,planet']);
    delete(e['own,planet']);
    delete(e['emp,planet']);
    delete(e['nap,planet']);
    delete(e['ally,planet']);
    delete(e['war,planet']);
    delete(e['wormhole']);
    delete(e[',asteroid']);
    delete(e[',wreckage']);
    delete(e['neutral,fleet']);
    delete(e['own,fleet']);
    delete(e['enemy,fleet']);
    delete(e['nap,fleet']);
    delete(e['npc,fleet']);
    delete(e['ga,fleet']);
    for (var item in e) {
        msg += '<br/><font color=red>'+e[item]+' &quot;'+item+'&quot; unknown item !</font>';
        delete(e[item]);
    }
    AddToMotd(msg,'<hr/>');
}

function Galaxy_Info() {
    a = new Array();
    row = id= 1;
    while (typeof $x('/html/body/div/div[4]/div/table/tbody/tr['+id+']/td[3]')[0] != 'undefined') {
        a[row-1] = new Array();
        a[row-1][1] = $x('/html/body/div/div[4]/div/table/tbody/tr['+id+']/td[3]')[0].innerHTML;
        a[row-1][2] = $x('/html/body/div/div[4]/div/table/tbody/tr['+id+']/td[5]')[0].innerHTML;
        a[row-1][3] = $x('/html/body/div/div[4]/div/table/tbody/tr['+id+']/td[7]')[0].innerHTML;
        row++;
        id = (row*2)-1;
    }
    data = new Array();
    data['ss']   = $x('/html/body/div/div/table/tbody/tr[3]/td[4]')[0].innerHTML;
    data['data'] = serialize(a);
    get_xml('galaxy_info', data);
}

function Wormhole() {
    var tables = $x('/html/body/div[2]/div/div/table/tbody/tr/td[3]/table', XPathResult.ORDERED_NODE_SNAPSHOT_TYPE);
    var i=1;
    var a=new Array();
    tables.forEach(function(paragraph) {  // Loop over every paragraph
        var nodess=paragraph.childNodes[1].childNodes[4].childNodes[7];
        var node=paragraph.childNodes[1].childNodes[6].childNodes[7];
        if (i==1) // départ
            a['IN'] = nodess.innerHTML+':'+node.innerHTML;
        else if (i==2) // arrivée
            a['OUT'] = nodess.innerHTML+':'+node.innerHTML;
        i++;
    });
    get_xml('wormhole', a);
}

function Planet() {
    var html = document.documentElement.innerHTML;

    var a=new Array();

    if (html.match(eval('/'+i18n[c_game_lang]['water']+'.+<td class=\\"font_white\\">(\\d+)%<\\/td>/i'))) {
        a['WATER'] = trim(RegExp.$1);
        if (debug) GM_log(i18n[c_game_lang]['water']+':'+a['WATER']);
        a['COORIN']= $x('/html/body/div/table/tbody/tr/td[3]/table/tbody/tr[4]/td[4]')[0].innerHTML;
        if (debug) GM_log(i18n[c_game_lang]['coords']+':'+a['COORIN']);
        a['BUILDINGS']=trim($x('/html/body/div/table/tbody/tr/td[3]/table/tbody/tr[6]/td[4]')[0].innerHTML);
        if (debug) GM_log(i18n[c_game_lang]['building']+':'+a['BUILDINGS']);
        get_xml('player', a);
    } else {
        if ($x('/html/body/div/table/tbody/tr/td[3]/table/tbody/tr[3]/td[2]')[0].innerHTML != i18n[c_game_lang]['coords']) {
            a['COORIN']= $x('/html/body/div/table/tbody/tr/td[3]/table/tbody/tr[4]/td[4]')[0].innerHTML;
        } else {
            a['COORIN']= $x('/html/body/div/table/tbody/tr/td[3]/table/tbody/tr[3]/td[4]')[0].innerHTML;
        }
        if (debug) GM_log(i18n[c_game_lang]['coords']+':'+a['COORIN']);
        row=4;
        while (typeof $x('/html/body/div/table/tbody/tr/td[3]/table/tbody/tr['+row+']/td[2]')[0] != 'undefined') {
            ress = $x('/html/body/div/table/tbody/tr/td[3]/table/tbody/tr['+row+']/td[2]')[0].innerHTML;
            for (i=0;i<10;i++)
                if (ress.indexOf(i18n[c_game_lang]['ress'+i])>0) {
                    a[i]= $x('/html/body/div/table/tbody/tr/td[3]/table/tbody/tr['+row+']/td[4]')[0].innerHTML;
                    break;
                }
            row++;
        }
        get_xml('planet', a);
    }

}

function Asteroid() {
    var html = document.documentElement.innerHTML;

    var a=new Array();
    if (html.match(/<td class="font_white">(\d+:\d+:\d+:\d+)<\/td>/))
        a['COORIN']= RegExp.$1;

    row = 4;
    while (typeof $x('/html/body/div[2]/table/tbody/tr/td[3]/table/tbody/tr['+row+']/td[2]')[0] != 'undefined') {
        ress = $x('/html/body/div[2]/table/tbody/tr/td[3]/table/tbody/tr['+row+']/td[2]')[0].innerHTML;
        for (i=0;i<10;i++)
            if (ress.indexOf(i18n[c_game_lang]['ress'+i])>0) {
                a[i]= $x('/html/body/div[2]/table/tbody/tr/td[3]/table/tbody/tr['+row+']/td[4]')[0].innerHTML;
                break;
            }
        row++;
    }

    get_xml('asteroid', a);
}

function Fleet() {

    var a = Array();
    var npc = false;
    a['owner']     = $x('/html/body/div/div/table/tbody/tr[2]/td[4]')[0].innerHTML;
    a['fleetname'] = $x('/html/body/div/div/table/tbody/tr[3]/td[4]')[0].innerHTML;
    a['coords']    = $x('/html/body/div/div/table/tbody/tr[4]/td[4]')[0].innerHTML;
    if (!a['coords'].match(/\d+\s+-\s+\d+\s+-\s+\d+\s+-\s+\d+/)) { // PNJ only ?
        npc = true;
        a['coords'] = $x('/html/body/div/div/table/tbody/tr[5]/td[4]')[0].innerHTML;
    }
    a['coords'] = a['coords'].replace(/\s*/g,'');
    if (npc && GM_getValue(c_prefix+'pnj_info',false)) get_xml('pnj', a);

    if (!npc) {
        a['owner'] = a['owner'].replace(/<\/?[^>]+>/gi, '')

    //    get_xml('userfleet', a);
    }
}

function FleetEdit() {
    var html = document.documentElement.innerHTML;
    var coords = '';
    if (html.match(eval('/'+i18n[c_game_lang]['coords']+'.+<td class=\\"font_white\\">(\\d+-\\d+-\\d+-\\d+)<\\/td>/')))
        coords=trim(RegExp.$1);
    AddToMotd("'"+coords+"'");
    GM_setValue(c_prefix+'lastcoords', coords);
}

function FleetTroop() {
    var lastpage=GM_getValue(c_prefix+'lastpage', '');
    var lastcoords=GM_getValue(c_prefix+'lastcoords', '');
    if (lastpage.indexOf('fleet/fleet_edit.php')<1) return;
    if (lastcoords == '') return;
    if (!GM_getValue(c_prefix+'galaxy_info',false)) return;

    Planets = unserialize(GM_getValue(c_prefix+'ownplanets', false));
    xpath = '/html/body/div[2]/div/div/div[2]/table/tbody/tr[4]/td[4]/font';
    i=0;
    while (typeof(Planets[i]) != 'undefined') {
        if (Planets[i]['Coord']==lastcoords) {
            xpath = '/html/body/div[2]/div/div/div[2]/table/tbody/tr[5]/td[4]/font';
            break;
        }
        i++;
    }
    var a = Array();
    a['EnnemyTroops'] = $x(xpath)[0].innerHTML;
    a['lastcoords']   = lastcoords;
    AddToMotd("Troops: "+a['EnnemyTroops']+" on "+a['lastcoords']);
    
    get_xml('troop_howmany', a);
}

function MaFiche() {
    var a = Array();

    prefixpts = '/html/body/div[2]/div/div/div/center';
    prefixright = '/html/body/div[2]/div/div/div[2]';
    id_td = 4;
    player = $x(prefixright+'/table/tbody/tr[2]/td[4]')[0].innerHTML;

    if (player.toLowerCase() != GM_getValue(c_prefix+'user','').toLowerCase()) return;

    a['Titre'] = $x(prefixright+'/table/tbody/tr[3]/td[4]/a')[0].innerHTML;
    a['Race'] = $x(prefixright+'/table/tbody/tr[4]/td[4]')[0].innerHTML;

    a['Commerce'] = $x(prefixright+'/table[2]/tbody/tr[2]/td[3]')[0].innerHTML;
    a['Recherche'] = $x(prefixright+'/table[2]/tbody/tr[4]/td[3]')[0].innerHTML;
    a['Combat'] = $x(prefixright+'/table[2]/tbody/tr[6]/td[3]')[0].innerHTML;
    a['Construction'] = $x(prefixright+'/table[2]/tbody/tr[8]/td[3]')[0].innerHTML;
    a['Economie'] = $x(prefixright+'/table[2]/tbody/tr[10]/td[3]')[0].innerHTML;
    a['Navigation'] = $x(prefixright+'/table[2]/tbody/tr[12]/td[3]')[0].innerHTML;

    a['GameGrade'] = $x(prefixpts)[0].innerHTML;
    i = a['GameGrade'].indexOf('>')+1;
    j = a['GameGrade'].indexOf('<', i);
    a['GameGrade'] = a['GameGrade'].substr(i, j-i);

    a['POINTS'] = $x(prefixpts+'/table/tbody/tr[4]/td['+id_td+']/b')[0].innerHTML;
//    a['pts_colonie'] = $x(prefixpts+'/table/tbody/tr[6]/td['+id_td+']')[0].innerHTML;
    a['pts_architecte'] = $x(prefixpts+'/table/tbody/tr[7]/td['+id_td+']')[0].innerHTML;
    a['pts_mineur'] = $x(prefixpts+'/table/tbody/tr[8]/td['+id_td+']')[0].innerHTML;
    a['pts_science'] = $x(prefixpts+'/table/tbody/tr[9]/td['+id_td+']')[0].innerHTML;
    a['pts_commercant'] = $x(prefixpts+'/table/tbody/tr[10]/td['+id_td+']')[0].innerHTML;
    a['pts_amiral'] = $x(prefixpts+'/table/tbody/tr[11]/td['+id_td+']')[0].innerHTML;
    a['pts_guerrier'] = $x(prefixpts+'/table/tbody/tr[12]/td['+id_td+']')[0].innerHTML;

    get_xml('mafiche', a);
}

function ownuniverse () {
    var Planet = Array();
    var i = 3;
    var j = 0;
    var p = 0;
    var k = '';

    while (trim($x('/html/body/div[2]/div/div[3]/div/table/tbody/tr/td['+i+']')[0].innerHTML) != '')
    {
        Planet[p] = Array();
        Planet[p]['Coord'] = trim($x('/html/body/div[2]/div/div[3]/div/table/tbody/tr/td['+i+']')[0].innerHTML);
        i += 2;
        p++;
    }
    GM_setValue(c_prefix+'ownplanets', serialize(Planet));

    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['Name'] = trim($x('/html/body/div[2]/div/div[2]/table/tbody/tr/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['control'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[3]/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['communication'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[5]/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['university'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[7]/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['technology'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[9]/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['gouv'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[11]/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['defense'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[13]/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['shipyard'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[15]/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['spacedock'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[17]/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['bunker'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[19]/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['tradepost'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[21]/td['+i+']')[0].innerHTML);
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j]['ressource'] = trim($x('/html/body/div[2]/div/div[3]/div/div/table/tbody/tr[23]/td['+i+']')[0].innerHTML);

    k='current_';// Stock sur planète
    div='2';
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Titane'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[3]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Cuivre'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[5]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Fer'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[7]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Aluminium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[9]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Mercure'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[11]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Silicium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[13]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Uranium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[15]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Krypton'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[17]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Azote'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[19]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Hydrogene'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[21]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));

    k='';// Production par heure
    div='3';
    for (i=3,j=0; j<p; i+=2,j++)  ///html/body/div[2]/div/div[3]/div/div[3]/table/tbody/tr[3]/td[3]
        Planet[j][k+'Titane'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[3]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Cuivre'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[5]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Fer'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[7]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Aluminium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[9]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Mercure'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[11]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Silicium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[13]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Uranium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[15]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Krypton'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[17]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Azote'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[19]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Hydrogene'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[21]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));

    k='bunker_';// Ressources dans le bunker
    div='4';
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Titane'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[3]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Cuivre'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[5]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Fer'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[7]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Aluminium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[9]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Mercure'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[11]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Silicium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[13]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Uranium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[15]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Krypton'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[17]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Azote'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[19]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Hydrogene'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[21]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));

    k='sell_';// Ventes par jours
    div='5';
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Titane'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[3]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Cuivre'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[5]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Fer'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[7]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Aluminium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[9]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Mercure'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[11]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Silicium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[13]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Uranium'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[15]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Krypton'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[17]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Azote'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[19]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));
    for (i=3,j=0; j<p; i+=2,j++)
        Planet[j][k+'Hydrogene'] = trim($x('/html/body/div[2]/div/div[3]/div/div['+div+']/table/tbody/tr[21]/td['+i+']')[0].innerHTML.replace(/\.*/g, ''));

    get_xml('ownuniverse', serialize(Planet));
}

function troop_battle() {

    if (!GM_getValue(c_prefix+'troops_battle',false)) return;

    var inf = Array();
    inf['date'] = $x('/html/body/div[2]/div/div/table[2]/tbody/tr/td/table/tbody/tr[2]/td[4]')[0].innerHTML;
    inf['coords'] = $x('/html/body/div[2]/div/div/table[2]/tbody/tr/td/table/tbody/tr[3]/td[4]')[0].innerHTML;



    reg= /shiplist\[(\d+)]\['caption'\] = '([^']+)'/g;
    m = document.documentElement.innerHTML.match(reg);
    var IdToPlayer=new Array();
    for (i = 0; i < m.length; i++) {
        m[i].search(reg);
        IdToPlayer[RegExp.$1] = RegExp.$2;
    }

    reg = /Array\(\'dmg\',(\d+),(\d+),(\d+)\);/g;
    m = document.documentElement.innerHTML.match(reg);
    if (m == null) {
        inf['nb_assault'] = 0;
        inf['pertes'] = new Array();
    } else {
        inf['nb_assault'] = m.length;
        var pertes=new Array();
        for (i = 0; i < m.length; i++) {
            m[i].search(reg);
            if (typeof pertes[IdToPlayer[RegExp.$2]] == 'undefined')
                pertes[IdToPlayer[RegExp.$2]] = parseInt(RegExp.$3);
            else
                pertes[IdToPlayer[RegExp.$2]] += parseInt(RegExp.$3);
        }
    }
    inf['pertes'] = serialize(pertes);

    reg= /shiplist\[(\d+)\]\['color'\] = 'green'/g;
    m = document.documentElement.innerHTML.match(reg);
    var arr=new Array();
    for (i = 0; i < m.length; i++) {
        m[i].search(reg);
        id = RegExp.$1;
        reg2= eval("/shiplist\\["+id+"\\]\\['caption'\\] = '([^']+)'/");
        document.documentElement.innerHTML.match(reg2);
        Player = RegExp.$1;
        reg2= eval("/shiplist\\["+id+"\\]\\['health_max'\\] = '([^']+)'/");
        document.documentElement.innerHTML.match(reg2);
        troops = parseInt(RegExp.$1);

        arr[Player] = troops;
    }
    inf['left'] = serialize(arr);

    reg= /shiplist\[(\d+)\]\['color'\] = 'red'/g;
    m = document.documentElement.innerHTML.match(reg);
    arr=new Array();
    for (i = 0; i < m.length; i++) {
        m[i].search(reg);
        id = RegExp.$1;
        reg2= eval("/shiplist\\["+id+"\\]\\['caption'\\] = '([^']+)'/");
        document.documentElement.innerHTML.match(reg2);
        Player = RegExp.$1;
        reg2= eval("/shiplist\\["+id+"\\]\\['health_max'\\] = '([^']+)'/");
        document.documentElement.innerHTML.match(reg2);
        troops = parseInt(RegExp.$1);

        arr[Player] = troops;
    }
    inf['right'] = serialize(arr);

    get_xml('troop_battle', inf);
}

function troop_log (mode) {

    if (!GM_getValue(c_prefix+'troops_battle',false)) return;

    var inf = Array();
    inf['date'] = $x('/html/body/div[2]/div/div/table/tbody/tr[4]/td[4]')[0].innerHTML;
    inf['msg'] = $x('/html/body/div[2]/div/div/table[2]/tbody/tr[2]/td')[0].innerHTML.replace(/<[^<]*>/g, '\n');
    inf['mode'] = mode;
    get_xml('troop_log', inf);
}

function gamelog_spooler () {
    ident = $x('/html/body/div[2]/div/div/table/tbody/tr[2]/td[4]')[0].innerHTML;

    if (ident.indexOf(i18n[c_game_lang]['troop_log_att']) == 0) troop_log('attacker');
    if (ident.indexOf(i18n[c_game_lang]['troop_log_def']) == 0) troop_log('defender');
}

function Options() {
    var area = $x('/html/body/div[2]/div/div/div/form/table[2]')[0];
    var i = 4;

    area.rows[i].innerHTML='';
    area.rows[i].appendChild(options_header(i18n[c_game_lang]['confheader']+' <small>('+version+')</small>'));

    i++;
    area.rows[i].innerHTML='';
    area.rows[i].appendChild(options_spacer());
    area.rows[i].appendChild(options_cell(i18n[c_game_lang]['conflink'], true));
    area.rows[i].appendChild(options_spacer());
    area.rows[i].appendChild(options_cell(options_text_s('eude_server',GM_getValue(c_prefix+'serveur','http://app216.free.fr/eu2/test/'),'250')));

    i++;
    area.rows[i].innerHTML='';
    area.rows[i].appendChild(options_spacer());
    area.rows[i].appendChild(options_cell(i18n[c_game_lang]['confuser'], true));
    area.rows[i].appendChild(options_spacer());
    area.rows[i].appendChild(options_cell(options_text_s('eude_user',GM_getValue(c_prefix+'user','test'),'100')));

    i++;
    area.rows[i].innerHTML='';
    area.rows[i].appendChild(options_spacer());
    area.rows[i].appendChild(options_cell(i18n[c_game_lang]['confpass'], true));
    area.rows[i].appendChild(options_spacer());
    area.rows[i].appendChild(options_cell(options_text_s('eude_pass',GM_getValue(c_prefix+'pass','test'),'100', true)));

    if (GM_getValue(c_prefix+'empire_maj',false) ) {
        i++;
        area.rows[i].innerHTML='';
        area.rows[i].appendChild(options_spacer());
        area.rows[i].appendChild(options_cell(i18n[c_game_lang]['active_empire'], true));
        area.rows[i].appendChild(options_spacer());
        area.rows[i].appendChild(options_cell(options_checkbox_s('eude_active_empire', GM_getValue(c_prefix+'active_empire',false))));
    }
    
    i++;
    area.rows[i].innerHTML='';
    area.rows[i].appendChild(options_spacer());
    area.rows[i].appendChild(options_cell(options_button_save('eude_save')));
    area.rows[i].appendChild(options_spacer(i18n[c_game_lang]['confspacer']));
    area.rows[i].appendChild(options_spacer());

    i++;	
    // rewrite delete accounts cells
//    id = i18n[c_game_lang]['confcells'];
//    var msg = area.rows[id].cells[3].innerHTML;
//    alert(msg);
//    area.rows[id].innerHTML='';
//    area.rows[id].appendChild(options_spacer());
//    var cell = options_cell(msg);
//    cell.setAttribute('colspan', '3');
//    area.rows[id].appendChild(cell);
    area.deleteRow(i);
    area.deleteRow(i);
    area.deleteRow(i);
    area.deleteRow(i);
    area.deleteRow(i);


    document.getElementById('eude_save').addEventListener('click', function() {
        var server = document.getElementById('eude_server').value;
        var user = document.getElementById('eude_user').value;
        var pass = document.getElementById('eude_pass').value;
        if (server.substr(-1)!='/') server+='/';
        GM_setValue(c_prefix+'serveur',server);
        GM_setValue(c_prefix+'user',user);
        GM_setValue(c_prefix+'pass',pass);
        if (GM_getValue(c_prefix+'empire_maj',false) ) {
            GM_setValue(c_prefix+'active_empire',document.getElementById('eude_active_empire').checked);
        } else {
            GM_setValue(c_prefix+'active_empire',false);
            GM_deleteValue(c_prefix+'empire_name');
        }

        get_xml('config', '');

    }, false);
}

function update_empire() {
    empire = $x('/html/body/div[2]/div[2]/div/div/table/tbody/tr[2]/td[4]');
    if (typeof empire[0] != 'undefined') {
        empire = trim(empire[0].innerHTML);
        GM_setValue(c_prefix+'empire_name',empire);
        if (debug) AddToMotd('Empire name:'+empire);
            
    }
}

function update_empire_members() {
    if (GM_getValue(c_prefix+'empire_name',false)) {
        var a = new Array();
        var data = new Array();
        var row=0;
        var tab = getElementsByClass("ei_mn");
        for (var i = 0; i < tab.length; i++) {
            a[row]=trim(tab[i].innerHTML);
            row++;
        }
        data['empire']=GM_getValue(c_prefix+'empire_name',"");
        data['data'] = serialize(a);
        get_xml('empire', data);
        GM_deleteValue(c_prefix+'empire_name');
        if (debug) AddToMotd(data['data']);
    }
}
/// Dispacheur
if (debug) AddToMotd('Page: '+c_page);

if (c_page.indexOf('user/settings_overview.php?area=options')>0)      Options();

if (GM_getValue(c_prefix+'actived','0')!='0') {
    if (c_page.indexOf('index.php')>0)                                  Index();
    if (c_page.indexOf('galaxy/galaxy_overview.php')>0)                Galaxy();
    if (c_page.indexOf('galaxy/galaxy_info.php')>0 &&
        GM_getValue(c_prefix+'galaxy_info',false) )               Galaxy_Info();
    if (c_page.indexOf('wormhole/wormhole_info.php?')>0)             Wormhole();

    if (c_page.indexOf('planet/planet_info.php?')>0) {
        if (c_page.indexOf('asteroid')>0 &&
            GM_getValue(c_prefix+'asteroid_info',false))             Asteroid();
        //if (c_page.indexOf('wreckage')>0)                               cdr();
        if (c_page.indexOf('plantype')<0 &&
            GM_getValue(c_prefix+'planet_info',false) )                Planet();
    }

    if (c_page.indexOf('fleet/fleet_info.php?')>0)                      Fleet();
    if (c_page.indexOf('fleet/commander_info.php?commander_id=')>0)   MaFiche();
    if (c_page.indexOf('fleet/fleet_edit.php')>0)                   FleetEdit();
    if (c_page.indexOf('fleet/fleet_troop.php')>0)                 FleetTroop();
    
    if (c_page.indexOf('building/control/control_overview.php?area=planet')>0)
        ownuniverse();
    if (c_page.indexOf('battle/battle_ground_report_info.php?area=ground_battle')>0)
        troop_battle();
    if (c_page.indexOf('gamelog/gamelog_view.php?gamelog_id')>0)
        gamelog_spooler();

    if  ((c_page.indexOf('empire/empire_info.php?empire_id=')>0
        ||c_page.indexOf('empire/empire_info.php?area=info&empire_id=')>0
        ||c_page.indexOf('empire/empire_info.php?user_id=')>0 )
    &&	GM_getValue(c_prefix+'empire_maj',false)
        && GM_getValue(c_prefix+'active_empire',false) )			update_empire();
    if  (	c_page.indexOf('empire/empire_info.php?area=member&empire_id=')>0
        &&	GM_getValue(c_prefix+'empire_maj',false)
        && GM_getValue(c_prefix+'active_empire',false)  )			update_empire_members();

    GM_setValue(c_prefix+'lastpage', c_page);
}

