# Révisions DevProdMed

# .env

Le fichier `.env`contient toutes les variables d'environnement qui permettent à Laravel d'intéragir avec des éléments externes, comme un serveur mail ou une base de données.

Pour le mailing, il faudra modifier les valeurs suivantes :

> ```
> ...
> MAIL_MAILER=smtp
> MAIL_HOST=smtp.heig-vd.ch
> MAIL_PORT=587
> MAIL_USERNAME= !!! votre_username_école !!!
> MAIL_PASSWORD= !!! votre_mot_de_passe_école !!!
> MAIL_ENCRYPTION=tls
> MAIL_FROM_ADDRESS= !!! votre_adresse_email_de_l_école !!!
> MAIL_FROM_NAME="${APP_NAME}"
> ...
> ```

Pour la base de données, ce sont ces valeurs :

```
DB_CONNECTION=sqlite
DB_HOST=
DB_PORT=
DB_DATABASE=/Users/mruffieux/Documents/_HEIG-VD/DevProdMed/monApp6/database/database.sqlite
DB_USERNAME=root
DB_PASSWORD=
```

Pour sqlite, il est possible d'uniquement garder la ligne `DB_CONNECTION=sqlite`.

# Routes

Les routes sont contenues dans le fichier `routes/web.php`. La route par défaut `/` retourne la vue `welcome`.

Pour définir une nouvelle route, il y a différentes méthodes :

- En appelant la Vue correspondante  : `Route::get('/', view('welcome');});`

- En appelant une méthode d'un contrôleur : `Route::get('logout', [LoginController::class, 'logout']);`

- En appelant directement un contrôleur de ressource : `Route::resource('cars', CarController::class);`

Cette dernière façon permet de directement gérer les différentes requêtes HTTP (get, post, ...).

Nous pouvons afficher toutes les routes actives sur le site via la commande : `php artisan route:list`.

## Paramètres de routes

On peut paramétrer une route selon la synthaxe suivante : 

```php
Route ::get('article/{n}/couleur/{c}', function($n, $c){
    return'article : ' . $n . ' de couleur ' . $c;
});
```

On peut forcer la route à respecter certaines règles via des expressions régulières (RegEx) :
```php
->where(['n'=>'[0-9]+', 'c'=>'rouge|vert|bleu']);
```

Nommer une route permet de rediriger facilement dessus, en ajoutant à la fin de la `Route::...` : `->name('home')``

Pour rediriger une route vers une autre, on peut utiliser son URL ou son nom, si cette dernière a été nommée : 

`Route::get(..., function() { return redirect('/home')});`
`Route::get(..., function() { return redirect()->route('home')});`

Il est possible de directement transmettre des données à la vue depuis les paramètres de la route : 

```php
Route ::get('article/{n}', function($n){
    return view('maVue')->with('numero', $n);
});
```

... que l'on récupèrera dans la vue de cette manière : `{{ $numero }}`.

Pour plusieurs paramètres, on transmet les paramètres via un tableau :

```php
Route ::get('article/{n}/couleur/{c}', function($n, $c){
    return view('maVue', ['numero'=>$n,'couleur'=>$c]);
});
```

## Requêtes HTTP

Il est donc aussi possible de définir une route différente sur la même URL, en différenciant les requêtes HTTP : `Route::get(..)`, `Route::post(...)`, etc.

Il est possible de récuperer les données d'un formulaire posté de cette manière : 

```php
use Illuminate\Http\Request;  // ne pas oublier d'indiquer où est la classe Request !

Route::post('traiteFormulaire', function (Request $request) {
 	dd($request->all());      // P.S. On voit notre jeton :-) (token)
});
```

# Vues

Une vue correspond à une page web affichant quelque chose. Les vues se trouvent dans le dossier `resources/views`.

Pour lier une vue à une route, on utilise la méthode suivante :

```php
Route ::get('article', function() { return view('vue1'); }) ;
```

## Blade

Blade est un outil de templating permettant la mise en forme de page web de manière aisée et facilement réutilisable.

Un template Blade est un fichier avec l'extension `.blade.php`. On peut dans ces fichiers injecter différents éléments, transmis depuis la Vue ou depuis le contrôleur, grâce à la commande `@yield`.

Il est aussi possible d'utiliser un template Blade dans un autre template, pour une meilleure structure.

Pour récupérer des données depuis la route, on utilise la synthaxe `{{$maVariable}}`.

Il est possible de générer une vue dynamiquement grâce à des boucles, comme par exemple `@foreach $mesVariables as $variable ... @endforeach`.

Il est possible d'insérer une pagination sur nos pages, grâce à `{{!! $links !!}}`. Il faut par contre configurer dans le contrôleur correspondant cette pagination :

```php
public function index()
{
    $cars=Car::with('user')
            ->orderBy('cars.created_at','desc')
            ->paginate(10); // le nombre d'objets par page
    $links=$cars->render();
    return view('view_voitures', compact('cars','links'));
}
```

## Formulaires

Une attaque `csrf` : L’objet de cette attaque est de transmettre à un utilisateur authentifié une requête HTTP falsifiée qui pointe sur une action interne au site, afin qu'il l'exécute sans en avoir conscience et en utilisant ses propres droits. L’utilisateur devient donc complice d’une attaque sans même s'en rendre compte. L'attaque étant actionnée par l'utilisateur, un grand nombre de systèmes d'authentification sont contournés.

Pour éviter ces attaques, on ajoute `@csrf` comme premier élément d'un formulaire. Le moteur Blade injectera alors un champ caché avec un token, une chaîne de caractères aléatoire unique.

Une fois le formulaire envoyé, on récupère ses données à travers la variable `$request`. On peut récupérer un input particulier en appelant son nom : `$request->input('nomInput')`.

On peut récupérer les erreurs de validation d'un formulaire via la variable `$errors`.

Lorsqu'on a une case à cocher, comme l'information n'est transmise que si la case est cochée, il faut faire un traitement spécial. *(dans la vue, pour l'affichage, et dans le contrôleur, pour l'enregistrement)*

# Contrôleurs

Un contrôleur gère les différents affichages des vues. C'est là que sont traitées les données avant d'être affichées.

Pour créer un nouveau contrôleur, on utilise la commande `php artisan make:controller MonControleur`. On peut ajouter le paramètre `-resource` pour un controleur de ressource.

Pour utiliser un controleur dans le fichier `routes/web.php`, il suffit de l'importer `use App\Http\Controllers\Auth\LoginController;`, puis de l'appeler de la manière suivante : `[LoginController::class, 'laMethode']`.

Pour envoyer des données du contrôleur vesr la vue, on ajoute à la route `->with(nomVariable, 'valeur')`.


## Mailing

La classe `Mail` permet d'envoyer un e-mail dans un controleur.

La méthode d'envoi est `send(...)`. Cette méthode prend 3 paramètres en argument : 

- La vue pour la mise en forme du mail;
- Un tableau de données provenant du formulaire : `$request`;
- La fonction de callback permettant de définir : 
    - L'adresse où envoyer le message
    - Le sujet du message. 
Ex :

```php
function($message) {
    $message->to('...une_adresse_email...')->subject('...un_sujet...');
};
```

## Contrôleur de ressources

Dans un contrôleur de ressource se trouve par défaut 7 méthodes : 

- `index()` : affiche toutes les données
- `create()` : renvoie le formulaire pour la saisie de données
- `store()` : traite et sauvegarde les données dans la table
- `show()` : affiche les données d'un élément
- `edit()` : renvoie le formulaire pour la modification de données
- `update()` : traite et modifie les données d'un objet dans la table
- `destroy()` : supprime un objet

## Middlewares

Un middleware permet d'effectuer un traitement à l'arrivée ou au départ d'une requête *(soit depuis la route, soit depuis le contrôleur)*.

Ils sont stockés dans le répertoire `app/Http/Middleware/`. On définit ses actions dans la méthode `handle()`.

```php
public function handle(Request $request, Closure $next)
{
    if ($request->user()->admin) {
        return $next($request);
    }
    return new RedirectResponse(url('play'));
}
```

## Authentification

Pour implémenter un système d'authentification, nous exécutons les méthodes suivantes :
- `composer require laravel/ui`, qui télécharge les classes nécessaires à l'installation
- `php artisan ui bootstrap --auth`, qui télécharge et installe les fichiers nécessaires à l'authentification
- `npm install && npm run dev`, qui installe et compile le tout.

Nous pouvons modifier la redirection par défaut après le login dans le fichier `app\Http\Controllers\Auth\LoginController.php` :

- changer la ligne :    `protected $redirectTo = RouteServiceProvider::HOME;`
- en :                  `protected $redirectTo = "/articles"; // par exemple`

Pour le logout, le changement se fait dans la méthode `logout()` du fichier `app\Http\Controllers\Auth\LoginController.php` :

```php
public function logout() {
    Auth::logout();
    return redirect('/articles');
}
```

... et ensuite ajouter la route permettant d'accéder à cette méthode : `Route::get('logout', [LoginController::class, 'logout']);`.

# Request

Si Laravel utilise la classe `Request` par défaut pour gérer les formulaires, il est possible de créer notre validateur personnalisé : `php artisan make:request nomDeRequete`.

Dans ce nouveau fichier, il faut modifier :

- Le retour de la méthode `authorize()` à `true`;
- Le retour de la méthode `rules()`, qui gère les règles de validation des différents champs. Par exemple : 

```php
return [
    'nom'=>'required|min:3|max:20|alpha',
    'email'=>'required|email',
    'texte'=>'required|max:250'
];
```

On modifiera alors la méthode du controleur gérant cette validation en ajoutant en paramètre notre objet `nomDeRequete $requete`.

# Base de données 

Une fois la base de données configurée dans notre fichier `.env`, nous créons la table 'migrations', qui permet à Laravel de gérer nos tables : `php artisan migrate:install`.

Pour créer une nouvelle table, nous devons d'abord créer le fichier gérant cette création : `php artisan make:migration create_unNomTable_table`.

Dans ce fichier se trouvent 2 méthodes : 
- `up()` : définit les champs, types et contraintes de la nouvelle table;
- `down()` : permet de supprimer la table.

La commande `php artisan migrate` crée toutes les tables définies dans le répertoire `laravel/databases/`.

Pour supprimer les tables, c'est la commande : `php artisan migrate:rollback`.

Laravel utilise un ORM (Object-Relational-Mapping) qui est **Eloquent**. Les éléments de la base de données sont ainsi représenté sous forme d'objets, ce qui simplifie les opérations CRUD.

Les classes permettant à Eloquent de communiquer avec notre BdD sont des **classes-modèles**, créées via la commande `php artisan make:model nomDeClasseModele` dans le répertoire `app/Models/`.

Dans ces classes, on indique à quelle table l'objet correspond. *(L'objet est nommé au singulier, tandis que la table est nommée au pluriel)*.

Pour enregistrer un objet dans notre table, on utilise la méthode : `monObjetDeClasseModele->save();`.

Pour peupler automatiquement une table, nous pouvons utiliser un **seeder** : `php artisan make:seeder MonSeeder`.

Dans cette classe, dans la méthode `run()`, on définit la manière dont les éléments sont créés : 

```php
DB::table('users')->delete(); // On supprime l'ancien contenu de la table
for ($i=1; $i<=10; $i++) {
    DB::table('users')->insert([
        'name'=>'Nom' . $i,
        'email'=>'email' . $i . '@gmx.ch',
        'password'=>Hash::make('password' . $i),
        'admin'=>rand(0,1)]); 
}
```

Une fois le seeder créé, on l'enregistre dans le fichier `app\database\seeders\DatabaseSeeder` :
```php
public function run() {
    $this->call(UsersTableSeeder::class);
    $this->call(ArticlesTableSeeder::class);
}
```

On lance ensuite le peuplement avec la commande : `php artisan db:seed`.

## Tinker

Tinker est un outil qui permet d'intéragir directement avec la base de données, en utilisant Eloquent. On le lance grâce à la commande : `php artisan tinker`.

Pour par exemple récupérer les articles de l'utilisateur 12 : `App\Models\User::find(12)->articles`.


## Types de bases de données

**Relation 1:n**

Un élément d'une table (T1) peut être lié à 0, 1 ou N éléments d'une autre table (T2).

Pour cela, nous définissons dans la table de migration de T2 la **clé étrangère** de T1 : 

```php
$table->foreign('id_personne')
    ->references('id')
    ->on('personnes');
```

On indique ensuite dans la classe-modèle de T1 qu'il peut avoir plusieurs objets T2 : 

```php
public function articles() {   // dans la classe modèle T1
    return $this->hasMany(App\Models\Articles::class);
}
```

... et dans la CM T2 qu'il n'a qu'un parent T1 :

```php
public function user() {    // dans la classe modèle Article
    return $this->belongsTo(App\Models\User::class);
}
```

La méthode se nomme au singulier pour le `1:`, et au plurier pour `:n`.

**Relation n:n**

Une lation de type n:n signifie qu'un enregistrement dans une table T1 peut être référencé par 0, 1 ou N enregistrements dans une table T2, et réciproquement.

Pour ce faire, nous avons besoin d'une **table pivot** *(donc 3 tables en tout)*.

La nomenclature d'une table pivot se fait de la manière suivante : les noms des deux tables, par **ordre alphabétique**, et au **singulier**.

*Ex: entreprise_personne*

Dans les classes-modèles des 2 tables, on ajoutera la méthode suivante : 

```php
public function entreprises() { // dans la CM Personne, et vice-versa dans la CM Entreprise
    return $this->belongsToMany(Entrepise::class);
}
```

Pour mettre à jour une table pivot, c'est la méthode du contrôleur `store()` qui s'en occupe *(comme dans chaque contrôleur ressources)*.

C'est ensuite la méthode Eloquent `->attach()` qui met à jour la table : 

```php
$article->motcles()->attach($mot_ref->id); // Attache à la table pivot un objet "article" et un objet "mot-clé"
```

## Gestion des utilisateurs

Lors de la création d'utilisateur, il est important de cacher le mot de passe, et qu'il ne soit pas enregistrer en clair dans la table.

Pour cela, nous utilisons la méthode `Hash::make($password)` dans notre classe-modèle `User`.
