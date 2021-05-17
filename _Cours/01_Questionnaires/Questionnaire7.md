Questionnaire 8
---------------

01.) Qu'est ce qu'une relation de type 1:n dans le domaine de la base de donnée ?

C'est une relation entre 1 élément A et 0,1 ou N éléments B. Par exemple, un auteur peut avoir écrit 0, 1 ou plusieurs livres, mais un livre n'aura qu'un auteur.

02.) Comment définit-on une clé étrangère dans une migration ?

`$table->foreign(...)->references(...)->on(...)`

03.) Quel est le rôle d'un ``Seeder`` dans Laravel ?

Peupler une table de manière automatisée.

04.) Lorsque l'on a plusieurs fichiers dans le répertoire ``database\seeds``, comment définit-on 
	 l'ordre d'exécution ?

On définit cet ordre dans la méthode `run()` du fichier `database/seeders/DatabaseSeeder`

05.) ``Eloquent`` (l'outil ORM) a besoin de classe(s) modèle(s) pour pouvoir interagir avec la base 
		de données.
		Comment définit-on la relation 1:n dans les deux classes modèles (Un ``User`` peut créer 
		plusieurs ``Article``) ?

Du côté `User` , on crée une méthode `articles()`, et l'on indique que l'utilisateur peut en avoir plusieurs : `$this->hasMany(Article::class)`.

Du côté `Article` , on crée une méthode `user()`, en indiquant qu'un article appartient à un utilisateur : `$this->belongsTo(User::class)`

06.) A quoi sert l'outil ``tinker`` dans Laravel ?

À interagir directement avec la base de données

07.) Quelle commande permet de lancer l'outil 'tinker' ?

`php artisan tinker`

08.) Quelle commande Eloquent permet de récupérer tous les articles de l'utilisateur ayant
		l'identifiant : 12 ?

`App\Model\User->findOrFail(12)->articles`

09.) Comment quitte-t-on l'outil 'tinker' ?

Grâce à la commande `quit`

10.) Qu'est ce qu'un ``middelware`` ?

C'est un élément qui permet de gérer les permissions (?).

11.) Quelle commande permet de créer un nouveau middleware ?

`php artisan make:middleware MonMiddleware`

12.) Quelle méthode doit-on implémenter dans le middleware pour que celui-ci fonctionne 
	    correctement ?

La méthode `handle()`.

13.) Comment gère-t-on l'authentification dans Laravel ?

Via le contrôleur `LoginController` (?) 

14.) Après le login on se retrouve à la racine de Laravel. Comment changer la destination après le log in ?

Dans le `LoginController`, on peut modifier la variable `$redirectTo`.

15.) Idem 14.) mais après le log out ?

Dans le `LoginController`, on peut modifier la redirection dans la méthode `logout` : `return redirect('urlCible');`. 