Questionnaire 5
---------------

1.) Quel fichier de Laravel permet la configuration de la connexion à un Système de Gestion de Base de 
      Données ?

​		.env

2.) A quoi sert la commande : ```php artisan migrate:install``` ?

​		initialise la base de données

3.) A quoi sert la commande : ```php artisan make:migration ...create_unNomTable_table...``` ?

​		à créer une table

4.) A quoi sert le contenu du fichier créé au point précédant.

​		À instancier la table

5.) Quelle(s) commande(s) permettent de créer / supprimer la nouvelle table dans la base de donnée ?

​		Up() & down()

6.) A quoi sert ```Eloquent``` dans Laravel ?



7.) Comment appelle-t-on les classes permettant à ```Eloquent``` de fonctionner ?



8.) Comment crée-t-on une ```classe-modèle``` ?



9.) Dans quel répertoire se trouvent les ```classes-modèles``` ?

​		/app/Models/

10.) Que doit-on mettre dans une ```classe-modèle``` ?

​		Le nom de la table à utiliser

11.) Quelle méthode permet la sauvegarde les informations d'un ```objet-modèle``` dans le base de données.

​		monObjet->save();