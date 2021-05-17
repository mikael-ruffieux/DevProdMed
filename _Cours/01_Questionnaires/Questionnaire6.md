# Questionnaire 6

1.) Comment crée-t-on un contrôleur de type ressource ?

​	php artisan make:controller UserController --resource

2.) Quelles méthodes se trouvent dans ce type de contrôleur ?

​	index(), create(), store(), show(), edit(), update(), destroy() ==> +/- CRUD

3.) Quel est le rôle de chacune de ces méthodes ?

 - index() : pour nous permettre d'afficher la liste des utilisateurs
 - create() : pour pouvoir renvoyer un formulaire permettant de créer un nouvel utilisateur
 - store() : pour sauvegarder les données d'un nouvel utilisateur
 - show() : pour afficher toutes les données d'un utilisateur
 - pour pouvoir renvoyer un formulaire permettant de modifier les données d'un utilisateur
 - update() : pour pouvoir modifier les données d'un utilisateur
 - destroy() : et enfin pour pouvoir supprimer les données d'un utilisateur

4.) Combien de routes doit-on créer pour accéder aux méthodes du contrôleur ?

​	Une seule : Route::resource('user', UserController::class);

5.) Quelle commande ```php artisan``` permet de lister toutes les routes d'un projet Laravel ?

​	php artisan route:list

6.) Quelle méthode de quelle classe permet d'encoder un mot de passe pour qu'on ne puisse pas le lire en
	clair dans la base de donnée ?

```php
public function setPasswordAttribute($password) {
	$this->attributes['password'] = Hash::make($password);
}
```

7.) Dans les vues que nous avons créé lors du dernier cours, à quoi sert l'instruction : {!! $links !!}

​		Affiche la barre de pagination

8.) Quelle modification doit-on apporter et dans quel fichier pour que chaque 'page' contienne 5 utilisateurs au lieu de 4 ?

​		Dans le contrôleur, on utiliser $users = User::paginate(4);

9.) A quoi doit-on veiller lors de la gestion d'une case à cocher ?

​		Comme l'information n'est transmise que si elle est cochée, il faut créer une méthode privée pour gérer le cas où rien de serait transmis.