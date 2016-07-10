# gesty

A Symfony project created on November 12, 2015, 8:41 pm.

### Deployment


First, in the gesty directory :

Because there is new changes since the last deployment

```sh
git pull origin master
```
If the directory "OLD_..." prevents the command to run, just add it in the .gitignore file.

All files are already presents, but there were copied by hand.

```sh
composer update --no-dev
```


```sh
php app/console doctrine:schema:update --force
```

```sh
php app/console cache:clear --env=prod --no-debug
```

Ensure the app/uploads is writable for Apache.

### !!! DO NOT FORGET TO ADD SCHOOL HOLIDAYS AND DAYS OFFS. !!! ###


### Import the shool holidays :

Login in admin


In "Parametres > Années scolaires"
Click "Ajouter" (top right, link "Liste d'actions"), then
enter :

- __Date de la rentrée scolaire :__
01/09/2016
- __Date de fin de l'année scolaire :__
07/07/2017

The file to import is in app/Resources/files :
Calendrier_Scolaire_Zone_B.ics

Once validated, ensure, the column "Vacances scolaires" in the list contains
4 holidays.

Add another year :
- __Date de la rentrée scolaire :__
04/09/2017
- __Date de fin de l'année scolaire :__
06/07/2018

Import the same file as previously (it also contains the holidays for 2017-2018).

### Set up days off ("jours fériés")

In admin,

In "Parametres > Jours fériés"

#### Jours fériés 2016 - If it already exists.

edit this data by clicking "Editer".
Then enter all missing days off if any then click "Mettre à jour et fermer"
It is required as other days off are setup due to this action (example : the 11th november is automatically defined thanks to the updating)

#### Jours fériés 2016 -  If it does not exists.

It is important because even if other dates for 2016 (e.g. 11th november 2016)
are updated automatically, we need to perform the following (event if the dates are passed).

Click on "Liste d'actions > Ajouter"

Enter :
- __Année__ : 2016
- __Lundi Pâques__ : 28/03/2016
- __Ascension__ : 05/06/2016
- __Vendredi Ascension__ : 06/05/2016
- __Lundi Pentecôte__ : 16/05/2016


Click on "Créer et retourner à la liste".

#### "Jours fériés" 2017

Add another "days off" for 2017 by clicking on "Liste d'actions > Ajouter"

Enter :
- __Année__ : 2017
- __Lundi Pâques__ : 17/04/2017
- __Ascension__ : 25/05/2017
- __Vendredi Ascension__ : 26/05/2017
- __Lundi Pentecôte__ : 05/06/2017

Click on "Créer et retourner à la liste".


#### To import old parent attached files

Use the command "gesty:db:migratefiles"
To know how to use it, enter :
```sh
php app/console help gesty:db:migratefiles
```
#####Example
If old files are in "~/Téléchargements/gesty_files/"
and the SQL file is "~/Téléchargements/file.sql"
Enter :
```sh
php app/console gesty:db:migratefiles ~/Téléchargements/file.sql ~/Téléchargements/gesty_files/ ./web/bundles/wcscantine/uploads ./app/uploads
```

### Test a registration

To be sure emails are sent, simply try a registration. You must receive an email to activate your account.

