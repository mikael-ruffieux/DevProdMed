# Examen du 18.05.2021

L'exercice 1 se trouve dans le dossier `examen1/laravel`.

Les exercices 2 et 3 se trouvent dans le répertoire `examen2/laravel`.

## Requêtes Tinker

Veuillez écrire les requêtes permettant :

- D'obtenir la liste de tous les projets

```php
App\Models\Projet::all()
```

- D'obtenir la liste des utilisateurs avec les projets sur lesquels ils travaillent

Pour un utilisateur en particulier :

```php
App\Models\Personne::findOrFail(1)->projets()->get()
```

Pour tous les utilisateurs : 

```php
foreach (App\Models\Personne::all() as $personne) {
  echo $personne->projets()->get();
}
```
*J'utilise ici la commande `echo` car le `return` ne retourne que les données de la première personne.*


- D'obtenir la liste des projets terminés avec leur participants

```php
foreach (App\Models\Projet::where('termine', 1)->get() as $projetTermine) {
  echo $projetTermine->personnes;
}
```