### PHP 5 ###

Par défaut free.fr propose php4.
Pour Passer à php5, créer un fichier “.htaccess” à la racine du ftp et mettez dedans:

```
<IfDefine Free>
php 1
</IfDefine>
```

### Erreur de sessions ###

Vous avez une erreur de ce genre:

```
 Failed to write session data (files). Please verify that the current setting of session.save_path is correct
```

Créer le dossier “ **sessions** ” à la racine de votre ftp (en minuscule!)