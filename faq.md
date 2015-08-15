# Questions #
  * [Pouvez vous m'installer un DataEngine](#Pouvez_vous_installer_un_DataEngine.md) ?
  * [Comment traduire eude](#Comment_traduire_eude.md) ?
  * [Page partiellement blanche](#Page_partiellement_blanche.md)
  * [Identification Impossible](#Identification_Impossible.md)
  * Erreur: [failed to open stream: Permission denied](#failed_to_open_stream:_Permission_denied.md)
  * [Modifier le rayon de scan](#Modifier_le_rayon_de_scan.md) (Addons Scanner)
  * [Comment devenir béta testeur ou développeur](#Comment_devenir_beta_testeur_ou_developpeur.md) sur eude
  * [FAQ](#FAQ.md) incomplète ?
# Réponses #

### Pouvez vous installer un DataEngine ###
  * La réponse est simple: NON !
> => Procédure d'[installation](Installation.md).

### Comment traduire eude ###
  * Copier le pack de base sur [le svn](http://code.google.com/p/eude/source/browse/trunk/last/tpl/lng/fr/)
  * Placer le dans un nouveau dossier de votre installation (/tpl/lng/xx)
  * Traduisez les fichiers un a un.
  * Et pensez a [faire partager](http://code.google.com/p/eude/issues/entry?template=i18n) votre traduction

### Page partiellement blanche ###
### Identification Impossible ###
  * Le point 4. de l'[Installation](Installation.md) n'a pas été remplit correctement.
  * Vous pouvez modifier le ficher /Script/Entete.php à la ligne où ce trouve `define('ROOT_URL', '/');`
> pour corriger votre erreur de configuration. Les explications sont dans le fichier lui-même.

### failed to open stream: Permission denied ###
  * Faire un chmod 777 ou 755 sur le dossier concerné.
  * un tuto: http://www.douf.info/tutos/tutos/filezilla/page-4-faire-chmod.php

### Modifier le rayon de scan ###
  * [issue 264](https://code.google.com/p/eude/issues/detail?id=264): Modifiez le fichier _/addons/scanner.conf.php_

### Comment devenir beta testeur ou developpeur ###
  * Savoir un minimum en PHP ! (qui l'aurais crus)
  * Savoir ce servir d'un svn pour récupérer directement sur le 'trunk'
  * Être capable de trouver, ou au moins cibler le problème,
  * Savoir faire un rapport complet, le 'sa marche pas', à oublier,
  * Savoir faire des remarques constructives,
  * Être Francophone de préférance,
  * Avoir un compte et faire [une demande](http://code.google.com/p/eude/issues/entry?template=Testeur) **complète**

### FAQ ###
  * Si vous avez une question **et** réponse pouvant être ajouté à la F.A.Q., Proposer là ci-desous.