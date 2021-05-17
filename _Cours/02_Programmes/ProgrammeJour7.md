# Jour 7 (Relation 1:N)

Aujourd'hui, nous allons créer deux tables dans une base de données et les mettre en relation. 

Il existe plusieurs type de relations, la plus répendue et la plus simple consiste à faire correspondre un enregistrement d'une table à plusieurs enregistrements d'une autre table. 

Ce type de relation est appelé : relation `1:N`

Voici quelques exemples :

- Une personne peut avoir plusieurs voitures, mais une voiture n'appartient qu'a un seul propriétaire.
- Une personne peut avoir plusieurs numéros de téléphones, un numéro correspond à une personne.
- Une personne peut rédiger plusieurs articles, un article a été rédigé par une personne.

## Blog

L'exemple que nous allons implémenter aujourd'hui est un blog dont voici les caractéristiques :

- Les utilisateurs de notre application pourront se connecter, rédiger des articles puis se déconnecter.
- Les visiteurs de notre site pourrons consulter tous les articles.
- Les administrateurs auront le droit de supprimer des articles.	

Commençons par la création d'un nouveau projet Laravel.

```
laravel new app_un_n/laravel
```

Pour développer rapidement, nous allons utiliser le SGBD `Sqlite` qui ne nécessite pas de serveur. Il nous faut juste un fichier (vide) nommé `database.sqlite` que nous créons dans le répertoire `laravel\database`

Configurons maintenant `Laravel` à l'aide du fichier `.env` pour une connexion à notre base de donnée `Sqlite`

```
DB_CONNECTION=sqlite
DB_HOST=
DB_PORT=
DB_DATABASE=C:\Users\...emplacementAppLaravel...\laravel\database\database.sqlite
DB_USERNAME=
DB_PASSWORD=
```

Pour tester que tout fonctionne, nous pouvons créer la table `migrations` à l'aide de la commande : 

```
php artisan migrate:install
```

> Remarque : Si une erreur se produit, c'est peut-être que vous avez oublié d'activer l'extension correspondante à `Sqlite` dans le fichier php.ini. Pour savoir quel fichier php.ini modifier, il faut taper la commande : 
>
> ```
> php --ini
> ```
>
> qui retourne par exemple : 
>
> ```
> Configuration File (php.ini) Path: C:\WINDOWS
> Loaded Configuration File:         C:\php\php-7.4.15\php.ini
> Scan for additional .ini files in: (none)
> Additional .ini files parsed:      (none)
> ```
>
> Il suffit d'éditer le fichier `php.ini` et d'activer l'extension (en enlevant le point virgule ;-)
>
> ```
> ...
> extension=pdo_sqlite
> ...
> ```

Déplaçons nous dans le répertoire `database\migrations` et éditons le fichier `2014_10_12_000000_create_users_table.php` et adaptons le contenu des méthodes `up()` et `down()` (comme nous l'avions fait lors du dernier cours) pour indiquer à Laravel les champs que doit contenir notre table `users`.

Créons à présent un nouveau fichier de migration (code qui va permettre de créer/supprimer une nouvelle table dans la bd) nommé create_articles_table à l'aide de la commande :

```
 php artisan make:migration create_articles_table
```

Et ajoutons le code nécessaire pour les différents champs :

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('titre',80);
            $table->text('contenu');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('restrict')
                    ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('articles', function(Blueprint $table) {
                $table->dropForeign('articles_user_id_foreign');
            });
        }
        Schema::dropIfExists('articles');
    }   
}
```

Reprenons les modifications que nous avions apporté lors du dernier cours à la migration de la table `users`.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
```

Nous pouvons maintenant créer ces tables dans notre base de donnée à l'aide de la commande :

```
php artisan migrate
```

Ce qui devrait afficher :

```
Migrating: 2014_10_12_000000_create_users_table                                                
Migrated:  2014_10_12_000000_create_users_table (95.41ms)                                      
Migrating: 2014_10_12_100000_create_password_resets_table                                      
Migrated:  2014_10_12_100000_create_password_resets_table (11.61ms)                            
Migrating: 2019_08_19_000000_create_failed_jobs_table                                          
Migrated:  2019_08_19_000000_create_failed_jobs_table (26.28ms)                                
Migrating: 2021_04_12_072143_create_articles_table                                             
Migrated:  2021_04_12_072143_create_articles_table (18.76ms)
```

Pour visualiser ces tables nous pouvons utiliser le programme `DB Browser for Sqlite`

## Peuplement des tables `users` et `articles` 

Pour faciliter nos tests nous allons créer des enregistrements (aléatoires) dans nos tables.

Pour ajouter des enregistrements dans une table, il nous faut des instructions. 

Les instructions se mettent dans une méthode (`run()`) 
Une méthode doit se trouver dans une classe. 
Il nous faut donc une nouvelle classe.

## Seeder

Une classe qui permet le peuplement d'une table se nomme un `seeder`. 

Pour créer un `seeder` nous avons la commande  `php artisan make:seeder NomNouvelleClasse`

Comme nous voulons des enregistrements dans la table `user` et dans la table `articles`, il nous faut deux classes `seeder` :

- `UsersTableSeeder`
- `ArticlesTableSeeder`

Lançons les commandes suivantes pour créer ces classes :

```
php artisan make:seeder UsersTableSeeder
```

```
php artisan make:seeder ArticlesTableSeeder
```

Ces deux classes sont maintenant disponibles dans le répertoire : `\laravel\database\seeds`

Modifions la classe `UsersTableSeeder.php` pour créer 10 utilisateurs :

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('users')->delete();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'name' => 'Nom' . $i,
                'email' => 'email' . $i . '@gmx.ch',
                'password' => Hash::make('password' . $i),
                'admin' => rand(0, 1)]);
        }
    }
}
```

Puis la classe `ArticlesTableSeeder.php` qui va créer 100 articles produits par des utilisateurs (déterminé aléatoirement)

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class ArticlesTableSeeder extends Seeder {

    private function randDate() {
        $nbJours = rand(-2800, 0);
        return Carbon::now()->addDays($nbJours);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('articles')->delete();
        for ($i = 1; $i <= 100; $i++) {
            $date = $this->randDate();
            DB::table('articles')->insert([
                'titre' => 'Titre' . $i,
                'contenu' => 'Contenu ' . $i . ' Lorem ipsum dolor sit amet, consectetur ' .
                'adipiscing elit. Proin vel auctor libero, quis venenatis ' .
                'augue. Curabitur a pulvinar tortor, vitae condimentum ' .
                'libero. Cras eu massa sed lorem mattis lacinia. ' .
                'Vestibulum id feugiat turpis. Proin a lorem ligula.',
                'user_id' => rand(1, 10),
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }
    }
}
```

## Ordre du peuplement des tables (`DatabaseSeeder`)

L'ordre du peuplement des tables est très important.

Comme les articles sont rattachés à des utilisateurs (`users`), il est important de créer d'abord les utilisateurs puis les articles.

Cet ordre (`users`-`articles`) se définit dans la méthode `run()` de la classe existante : `\laravel\database\seeds\DatabaseSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
    }
}
```

## Création des enregistrements

 Lançons le peuplement de nos deux tables :

```
php artisan db:seed
```

> Si la commande précédente génère une erreur, c'est que `Laravel` n'a pas "vu" nos nouvelles classes.
>
> Pour s'assurer que Laravel prenne en compte nos deux nouvelles classes 
> (`UsersTableSeeder`, `ArticlesTableSeeder`) lançons la commande :
>
> ```
> composer dump-autoload
> ```

Lorsque tout s'est bien passé, le message suivant s'affiche :

```
Seeding: Database\Seeders\UsersTableSeeder                                                     
Seeded:  Database\Seeders\UsersTableSeeder (900.50ms)                                          
Seeding: Database\Seeders\ArticlesTableSeeder                                                  
Seeded:  Database\Seeders\ArticlesTableSeeder (513.95ms)                                       
Database seeding completed successfully.
```

A l'aide de l'outil `DB Browser for Sqlite` nous pouvons constater que nos tables sont bien remplies :slightly_smiling_face:

Voici les enregistrements de la table `users` :

![Users](img\Users.png)

Voici quelques enregistrements (16/100) de la table articles :

![Articles](img\Articles.png)

Nous pouvons bien sûr aussi utiliser l'outil `tinker` pour s'assurer de la présence des enregistrements dans nos deux tables (`users` et `articles`)  

```
php artisan tinker
```

```
>>> use App\Models\User;
>>> User::limit(10)->get();
```

Qui nous retourne :

```
=> Illuminate\Database\Eloquent\Collection {#4289                                              
     all: [                                                                                    
       App\Models\User {#4290                                                                  
         id: "1",                                                                              
         name: "Nom1",                                                                         
         email: "email1@gmx.ch",                                                               
         email_verified_at: null,                                                              
         #password: "$2y$10$q18WSHTiCOIqIF3vX3qgcOth7bNNj4.QGob6RLAFLWVGJjCGw0s6W",            
         admin: "1",                                                                           
         #remember_token: null,                                                                
         created_at: null,                                                                     
         updated_at: null,                                                                     
       },                                                                                      
       App\Models\User {#4291                                                                  
         id: "2",                                                                              
         name: "Nom2",                                                                         
         email: "email2@gmx.ch",                                                               
         email_verified_at: null,                                                              
         #password: "$2y$10$3vCvvnqmQLLcmeHRRwxKDeOe/YhiTe8s6nGxcuQqPQh.R0gsfJGQW",            
         admin: "1",                                                                           
         #remember_token: null,                                                                
         created_at: null,                                                                     
         updated_at: null,                                                                     
       },                                                                                      
       App\Models\User {#4292                                                                  
         id: "3",                                                                              
         name: "Nom3",                                                                         
         email: "email3@gmx.ch",                                                               
         email_verified_at: null,                                                              
         #password: "$2y$10$Pn52v.j6z3oJvohesTkhKevrYDBFqYCjeVoUo8wLoVy0d52vXHzP6",            
         admin: "1",                                                                           
         #remember_token: null,                                                                
         created_at: null,                                                                     
         updated_at: null,                                                                     
       },                                                                                      
       App\Models\User {#4293                                                                  
         id: "4",                                                                              
         name: "Nom4",                                                                         
         email: "email4@gmx.ch",                                                               
         email_verified_at: null,                                                              
         #password: "$2y$10$JDe60bPfQV6rCwQFwMT4xOk3cxr.FfmG0K1SCSVm71yTnsImKjDM2",            
         admin: "1",                                                                           
         #remember_token: null,                                                                
         created_at: null,                                                                     
         updated_at: null,                                                                     
       },                                                                                      
       App\Models\User {#4294                                                                  
         id: "5",                                                                              
         name: "Nom5",                                                                         
         email: "email5@gmx.ch",                                                               
         email_verified_at: null,                                                              
         #password: "$2y$10$bo4UnED8rULEcCIJj4F77O0Wde8DL3w1ET9kvKhptYuPnz7e0QF7C",            
         admin: "1",                                                                           
         #remember_token: null,                                                                
         created_at: null,                                                                     
         updated_at: null,                                                                     
       },                                                                                      
       App\Models\User {#4295                                                                  
         id: "6",                                                                              
         name: "Nom6",                                                                         
         email: "email6@gmx.ch",                                                               
         email_verified_at: null,                                                              
         #password: "$2y$10$JyPnCoPASuYUBqqAl148Xe0UA6VjfezRTqu8mVsQObi5mF/gXzYOG",            
         admin: "0",                                                                           
         #remember_token: null,                                                                
         created_at: null,                                                                     
         updated_at: null,                                                                     
       },                                                                                      
       App\Models\User {#4296                                                                  
         id: "7",                                                                              
         name: "Nom7",                                                                         
         email: "email7@gmx.ch",                                                               
         email_verified_at: null,                                                              
         #password: "$2y$10$3kUhoK55uhFORP1aCa2HSOX6TE8NNgjgoxmtHQpSzTBpz.vzC/xua",            
         admin: "1",                                                                           
         #remember_token: null,                                                                
         created_at: null,                                                                     
         updated_at: null,                                                                     
       },                                                                                      
       App\Models\User {#4297                                                                  
         id: "8",                                                                              
         name: "Nom8",                                                                         
         email: "email8@gmx.ch",                                                               
         email_verified_at: null,                                                              
         #password: "$2y$10$zI1hx36Z.KbNVIqHixZTbesICB5SxutPXJTERoMtteOZHMWMQ5G76",            
         admin: "1",                                                                           
         #remember_token: null,                                                                
         created_at: null,                                                                     
         updated_at: null,                                                                     
       },                                                                                      
       App\Models\User {#4298                                                                  
         id: "9",                                                                              
         name: "Nom9",                                                                         
         email: "email9@gmx.ch",                                                               
         email_verified_at: null,                                                              
         #password: "$2y$10$OBNfDF.s90RKw1q0dUW6/evgRTDpQQ8m8uBDlqDoUERYIeiVL9ku2",            
         admin: "1",                                                                           
         #remember_token: null,                                                                
         created_at: null,                                                                     
         updated_at: null,                                                                     
       },                                                                                      
       App\Models\User {#4299                                                                  
         id: "10",                                                                             
         name: "Nom10",                                                                        
         email: "email10@gmx.ch",                                                              
         email_verified_at: null,                                                              
         #password: "$2y$10$/x5h8879iQdb6SwxIwUQhu4l.NRiDZQ.uiZIjQv.qHdkTjHegsT02",            
         admin: "1",                                                                           
         #remember_token: null,                                                                
         created_at: null,                                                                     
         updated_at: null,                                                                     
       },                                                                                      
     ],                                                                                        
   }
```

Tinker utilise la classe "modèle"  (`\laravel\app\Models\User.php`) pour aller rechercher les enregistrements de la table `users`.

Pour que nous puissions voir nos articles à l'aide de `tinker`, nous devons d'abord créer la classe "modèle" `Article.php`. 

> Quittons `tinker`
>
> ```
> >>> quit
> ```

Créons notre classe "modèle" à l'aide de la commande :

```
php artisan make:model Article
```

## Relation 1:N

La relation 1:N se définit dans les deux classes "modèle" impliquées (`User.php`, `Article.php`)

Dans la classe `User.php` nous devons indiquer qu'un utilisateur peut posséder plusieurs articles.

Dans la classe `Articles.php` nous devons indiquer qu'un article appartient à un utilisateur.

Reprenons les modifications que nous avions apporté lors du dernier cours à la classe `\laravel\app\Models\User.php` et ajoutons la méthode `articles()`

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Permet d'encoder le mot de passe
     * @param type $password Le mot de passe
     */
    public function setPasswordAttribute($password) {
        $this->attributes['password'] = Hash::make($password);
    }
    
    // DEFINITON DE LA RELATION x:N
    public function articles() {                 // NOUVEAU !!!!!!!!
        return $this->hasMany(Article::class);   // Relation (1:)N
    }                                            // NOUVEAU !!!!!!!!
}
```

Et complétons la classe "modèle" `\laravel\app\Models\Article.php` :

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    
    // sans rien indiquer de plus, Laravel rattache automatiquement 
    // ce modèle à la table "articles"
    // Il cherche une table nommée comme la classe mais en rajoutant un 's'
    // => nom de la classe Article => recherche la table "articles" dans la bd
    
    protected $fillable=['titre','contenu','user_id'];  // pour plus tard ;-)
    
    public function user() {			        // NOUVEAU !!!!!!!!!!
        return $this->belongsTo(User::class);    // Relation 1(:N)
    }                                            // NOUVEAU !!!!!!!!!!
}
```

En résumé, la relation 1:N se traduit par les deux méthodes :

```php
// au singulier dans la classe "modèle" Article.php
public function user() {						  // NOUVEAU !!!!!!!!!!
	return $this->belongsTo(User::class);           // Relation 1(:N)
}   											// NOUVEAU !!!!!!!!!!
```

```php
// au pluriel dans la classe "modèle" User.php
public function articles() {                  // NOUVEAU !!!!!!!!
	return $this->hasMany(Article::class);    // Relation (1:)N
}                                             // NOUVEAU !!!!!!!!
```
Nous pouvons maintenant retourner dans l'outil `tinker`

```
php artisan tinker
```

Demandons maintenant (à `tinker`) de nous afficher les articles appartenant à l'utilisateur ayant l'identifiant : 1

```
>>> App\Models\User::findOrFail(1)->articles
```

`Tinker` nous affiche alors :

```
=> Illuminate\Database\Eloquent\Collection {#4231                                              
     all: [                                                                                    
       App\Models\Article {#4302                                                               
         id: "5",                                                                              
         created_at: "2014-07-01 08:32:15",                                                    
         updated_at: "2014-07-01 08:32:15",                                                    
         titre: "Titre5",                                                                      
         contenu: "Contenu 5 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel
 auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cr
as eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",    
         user_id: "1",                                                                         
       },                                                                                      
       App\Models\Article {#4303                                                               
         id: "11",                                                                             
         created_at: "2018-07-12 08:32:15",                                                    
         updated_at: "2018-07-12 08:32:15",                                                    
         titre: "Titre11",                                                                     
         contenu: "Contenu 11 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ve
l auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. C
ras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",   
         user_id: "1",                                                                         
       },                                                                                      
       App\Models\Article {#4304                                                               
         id: "13",                                                                             
         created_at: "2020-04-18 08:32:15",                                                    
         updated_at: "2020-04-18 08:32:15",                                                    
         titre: "Titre13",                                                                     
         contenu: "Contenu 13 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ve
l auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. C
ras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",   
         user_id: "1",                                                                         
       },                                                                                      
       App\Models\Article {#4305                                                               
         id: "50",                                                                             
         created_at: "2016-01-06 08:32:15",                                                    
         updated_at: "2016-01-06 08:32:15",                                                    
         titre: "Titre50",                                                                     
         contenu: "Contenu 50 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ve
l auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. C
ras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",   
         user_id: "1",                                                                         
       },                                                                                      
       App\Models\Article {#4306                                                               
         id: "54",                                                                             
         created_at: "2020-03-07 08:32:15",                                                    
         updated_at: "2020-03-07 08:32:15",                                                    
         titre: "Titre54",                                                                     
         contenu: "Contenu 54 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ve
l auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. C
ras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",   
         user_id: "1",                                                                         
       },                                                                                      
       App\Models\Article {#4307                                                               
         id: "57",                                                                             
         created_at: "2015-12-29 08:32:15",                                                    
         updated_at: "2015-12-29 08:32:15",                                                    
         titre: "Titre57",                                                                     
         contenu: "Contenu 57 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ve
l auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. C
ras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",   
         user_id: "1",                                                                         
       },                                                                                      
       App\Models\Article {#4308                                                               
         id: "70",                                                                             
         created_at: "2021-02-12 08:32:15",                                                    
         updated_at: "2021-02-12 08:32:15",                                                    
         titre: "Titre70",                                                                     
         contenu: "Contenu 70 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ve
l auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. C
ras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",   
         user_id: "1",                                                                         
       },                                                                                      
       App\Models\Article {#4309                                                               
         id: "83",                                                                             
         created_at: "2019-12-19 08:32:15",                                                    
         updated_at: "2019-12-19 08:32:15",                                                    
         titre: "Titre83",                                                                     
         contenu: "Contenu 83 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ve
l auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. C
ras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",   
         user_id: "1",                                                                         
       },                                                                                      
       App\Models\Article {#4310                                                               
         id: "92",                                                                             
         created_at: "2019-05-27 08:32:15",                                                    
         updated_at: "2019-05-27 08:32:15",                                                    
         titre: "Titre92",                                                                     
         contenu: "Contenu 92 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ve
l auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. C
ras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",   
         user_id: "1",                                                                         
       },                                                                                      
       App\Models\Article {#4311                                                               
         id: "94",                                                                             
         created_at: "2015-12-15 08:32:15",                                                    
         updated_at: "2015-12-15 08:32:15",                                                    
         titre: "Titre94",                                                                     
         contenu: "Contenu 94 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ve
l auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. C
ras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",   
         user_id: "1",                                                                         
       },                                                                                      
     ],                                                                                        
   }
>>>
```

Le résultat diffère un peu chez vous car le peuplement des tables s'est fait de manière aléatoire.
(Voir le code des `Seeders` ci-dessus)
Il y a peut-être plus ou peut être moins d'articles (N articles)

Nous pouvons maintenant demander à tinker à qui appartient l'article ayant l'identifiant : 10

```
App\Models\Article::findOrFail(10)->user
```

Tinker nous répond :

```
=> App\Models\User {#4076                                                                      
     id: "6",                                                                                  
     name: "Nom6",                                                                             
     email: "email6@gmx.ch",                                                                   
     email_verified_at: null,                                                                  
     #password: "$2y$10$JyPnCoPASuYUBqqAl148Xe0UA6VjfezRTqu8mVsQObi5mF/gXzYOG",                
     admin: "0",                                                                               
     #remember_token: null,                                                                    
     created_at: null,                                                                         
     updated_at: null,                                                                         
   }                                                                                           
>>>         
```

L'identifiant de l'utilisateur est surement différent chez vous ! Par contre ce qui est sûr, c'est qu'il y a qu'un seul utilisateur (1 utilisateur)

Relation 1:N                =>            1 utilisateur : N articles :slightly_smiling_face:

Tout est en place au niveau des données. Nous pouvons maintenant implémenter notre application.

## Contrôleur

Créons notre contrôleur. (Ce sera comme lors du dernier cours un contrôleur de type ressource)

```
php artisan make:controller ArticleController --resource
```

Par défaut, ce contrôleur possède les méthodes suivantes :

- index()
- create()
- store(Request $request)
- show($id)
- edit($id)
- update(Request $request, $id)
- destroy()

Mais cela ne veut pas dire que nous devons toutes les avoir. 

Nous pouvons supprimer celles que nous utiliserons pas. (`show`, `edit`, `update`)

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
```

## Route

```php
Route::resource('articles', ArticleController::class, ['except'=>['show','edit','update']]);
```

> N'oublions pas le `use` pour la classe `ArticleController`

Comme nous n'utiliserons pas les trois méthodes  (`show`, `edit`, `update`) nous pouvons supprimer les routes qui y mènent (`['except'=>['show','edit','update']]`)

> Rappel : Pour voir toutes les routes et leur méthodes associées dans le contrôleur il y a la commande que nous avons vu lors du dernier cours : 
>
> ```
> php artisan route:list
> ```

## Template

Voici le template Blade `\resources\view\template.blade.php`

```php+HTML
<!doctype html>
<html lang='fr'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>
            Mon blog
        </title>
        <link media="all" type="text/css" rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
        <style> textarea {resize:none} </style>
    </head>
    <body>
        <header class="jumbotron">
            <div class="container">
                <h1 class="page-header"><a href="{{route('articles.index')}}">Mon blog</a></h1>
                @yield('header')
            </div>
        </header>
        <div class="container">
            @yield('contenu')
        </div>
    </body>
</html>
```

## Vue (Liste des articles)

Voici la vue Blade `\resources\view\view_articles.blade.php`

```php+HTML
@extends('template')

@section('header')
@endsection

@section('contenu')
{!!$links!!}
@foreach($articles as $article)
<article class="row bg-primary">
    <div class="col-md-12">
        <header>
            <h1>{{$article->titre}}</h1>
        </header>
        <hr>
        <section>
            <p>{{$article->contenu}}</p>
            <em class="pull-right">
                <span class="gliphicon glyphicon-pencil"></span>
                {{$article->user->name}} le {!! $article->created_at->format('d-m-Y') !!}
            </em>
        </section>
    </div>
</article>
<br>
@endforeach
{!! $links !!}
@endsection
```

## Mise à jour du contrôleur

Nous pouvons ajouter les instructions permettant d'obtenir la liste des articles

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    protected $nbArticlesParPage = 4;
    
    public function index() {
        $articles=Article::with('user')
                ->orderBy('articles.created_at','desc')
                ->paginate($this->nbArticlesParPage);
        $links=$articles->render();
        return view('view_articles', compact('articles','links'));
    }
    
    public function create() {}
    
    public function store(Request $request) {}
    
    public function destroy($id) {}
}
```

Nous pouvons tester l'état actuel de notre application en lançant notre application :

```
http://localhost:8000/ ... /articles
```

Pour obtenir une navigation plus aisée entre les différentes pages, nous pouvons configurer Laravel pour l'utilisation de `bootstrap` pour le rendu. [Documentation](https://laravel.com/docs/8.x/pagination#using-bootstrap)

`app\Providers\AppServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Paginator::useBootstrap();
    }

}
```

Voici ce que l'on obtient :

![ListeArticles](img\ListeArticles.png)

Pour qu'un utilisateur puisse créer des articles, il doit se connecter.

Nous allons nous occuper maintenant de l'authentification.

## Authentification

Pour implémenter une authentification dans Laravel, il nous faut l'outil `npm`. (Pour plus d'info : [npm](https://www.npmjs.com/get-npm))

Pour savoir si `npm` est installé sur votre ordinateur, il suffit de taper la commande suivante :

```
npm
```

Si rien ne se passe, il faut l'installer. 

`npm` fait partie de Node.js. En installant Node.js, `nmp` sera installé automatiquement.

```
https://nodejs.org/fr/download/
```

Vous pouvez validez toutes les options proposées par défaut.

Une fois l'installation terminée, il faut fermer la fenêtre de la ligne de commande, puis l'ouvrir à nouveau.

En tapant la commande : 

```
npm
```

Celle-ci retournera :

```
Usage: npm <command>

where <command> is one of:
    access, adduser, audit, bin, bugs, c, cache, ci, cit,
    clean-install, clean-install-test, completion, config,
    create, ddp, dedupe, deprecate, dist-tag, docs, doctor,
    edit, explore, fund, get, help, help-search, hook, i, init,
    install, install-ci-test, install-test, it, link, list, ln,
    login, logout, ls, org, outdated, owner, pack, ping, prefix,
    profile, prune, publish, rb, rebuild, repo, restart, root,
    run, run-script, s, se, search, set, shrinkwrap, star,
    stars, start, stop, t, team, test, token, tst, un,
    uninstall, unpublish, unstar, up, update, v, version, view,
    whoami

npm <command> -h  quick help on <command>
npm -l            display full usage info
npm help <term>   search for help on <term>
npm help npm      involved overview

Specify configs in the ini-formatted file:
    C:\Users\Enzo\.npmrc
or on the command line via: npm <command> --key value
Config info can be viewed via: npm help config
```

Ce qui nous indique que `npm` est installé.

Nous pouvons donc passer à l'implémentation de l'authentification.

Demandons maintenant à Laravel de télécharger le nécessaire pour l'interface utilisateur de l'authentification.

```
composer require laravel/ui
```

> Remarque : L'installation nécessite un peu de temps...

Une fois que la base est installée, nous pouvons préciser le type d'interface utilisateur que nous voulons utiliser pour l'authentification. (Nous allons utiliser `bootstrap`)

```
php artisan ui bootstrap --auth
```

Puis installons le tout comme proposé :

```
npm install && npm run dev
```

> Remarque : En cas d'erreur, relancez la commande une seconde fois.

Voilà, c'est installé.

Dans le répertoire `/resources/views` nous pouvons voir un nouveau répertoire `/auth` contenant des vues supplémentaires.

Pour voir que l'authentification est fonctionnelle, il suffit de lancer notre application.

![Authentification](img\Authentification.png)

Nous pouvons observer en haut à droite de la fenêtre, deux nouveau liens `Log in` et `Register`

Cliquons sur `Log In` et identifions nous avec les informations suivantes : 

- `E-Mail Address` : `email1@gmx.ch`
- `Password` : `password1`

Nous obtenons le résultat suivant :

![Logged](img\Logged.png)

Pour rediriger l'utilisateur après son identification nous pouvons modifier le contrôleur qui a été ajouté lors de la mise en place de l'authentification.

Il s'agit du contrôleur `app\Http\Controllers\Auth\LoginController.php`

Il suffit de changer la ligne 29 :

```
protected $redirectTo = RouteServiceProvider::HOME;
```

en 

```
protected $redirectTo = "/articles";
```

## Middleware

Nous allons maintenant configurer un `middleware` pour la gestion des droits.

Un `middleware` effectue un traitement à l'arrivée d'une requête ou à son départ.
Par exemple, la gestion des sessions ou des cookies se fait dans un middleware.
Nous pouvons avoir autant de `middleware` que l'on veut. 
Les `middleware` se chaînent les uns à la suite des autres.
Chacun effectue son traitement et transmet la requête ou la réponse au suivant.
Un `middleware` n'est rien d'autre qu'une classe.

Créons notre `middleware` `Admin.php` à l'aide de la commande :

```
php artisan make:middleware Admin
```

Nous pouvons éditer notre classe `Admin.php` qui se trouve dans le répertoire : `app\Http\Middleware\`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
```

Modifions la méthode `handle(...)` pour indiquer à Laravel que si l'utilisateur n'est pas un administrateur, on le redirige sur l'affichage des articles du blog.

```php
public function handle($request, Closure $next) {
	if ($request->user()->admin) {
    	return $next($request);
    }
    return new RedirectResponse(url('articles'));
}
```

A chaque `middleware` créé, il faut lui donner un nom. On appelle cela un nom de filtre.

Le nommage d'un `middleware` se fait dans la propriété `$routeMiddleware` de la classe  `app\Http\Kernel.php`. Il s'agit d'un tableau dans lequel nous ajoutons une clé-valeur.

Voici la valeur par défaut :

```
protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
```

et voici sa nouvelle valeur : 

```
protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\Admin::class,
    ];
```

Nous y avons indiqué que le filtre nommé `admin` fait référence au `middleware : app\Http\Middleware\Admin.php`

Nous pouvons maintenant utiliser notre `middleware` à l'aide de son nom de filtre `admin`

Adaptons maintenant notre application.

Modifions notre contrôleur :

- activation des deux filtres (1 existant `auth` + celui que l'on vient de créer `admin`) dans le constructeur.
- redirection vers le formulaire pour la création d'un nouvel article
- enregistrement des données d'un nouvel article
- suppression d'un article d'après si identifiant

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Article;

class ArticleController extends Controller
{
    protected $nbArticlesParPage = 4;
    
    public function __construct() {
        $this->middleware('auth', ['except'=>'index']);
        $this->middleware('admin', ['only'=>'destroy']);
    }
    
    public function index()
    {
        $articles=Article::with('user')
                ->orderBy('articles.created_at','desc')
                ->paginate($this->nbArticlesParPage);
        $links=$articles->render();
        return view('view_articles', compact('articles','links'));
    }
    
    public function create() {
        return view('view_ajoute_article');
    }
    
    public function store(ArticleRequest $request) {
       $inputs=array_merge($request->all(), ['user_id'=>$request->user()->id]);
       Article::create($inputs);
       return redirect(route('articles.index'));
    }
    
    public function destroy($id) {
        Article::findOrFail($id)->delete();  
        return redirect()->back();
    }
}
```

Modifions notre vue permettant d'afficher la liste des articles `\resources\view\view_articles.blade.php`

```php+HTML
@extends('template')

@section('header')
@if(Auth::check())
<div class="btn-group pull-right">
    <a href='{{route("articles.create")}}' class='btn btn-info'>Cr&eacute;er un article</a>
    <a href='{{url("logout")}}' class='btn btn-warning'>Deconnexion</a>
</div>
@else 
<a href='{{url("login")}}' class='btn btn-info pull-right'>Se connecter</a>
@endif
@endsection

@section('contenu')
@if(isset($info))
<div class='row alert alert-info'> {{$info}}</div>
@endif
{!!$links!!}
@foreach($articles as $article)
<article class="row bg-primary">
    <div class="col-md-12">
        <header>
            <h1>{{$article->titre}}</h1>
        </header>
        <hr>
        <section>
            <p>{{$article->contenu}}</p>
            @if(Auth::check() and Auth::user()->admin)
            <form method="POST" action="{{route('articles.destroy', [$article->id])}}" accept-charset="UTF-8">
                @csrf
                @method('DELETE')
                <input class="btn btn-danger btn-xs" onclick="return confirm('Vraiment supprimer cet article ?')" type="submit" value="Supprimer cet article">
            </form>
            @endif
            <em class="pull-right">
                <span class="gliphicon glyphicon-pencil"></span>
                {{$article->user->name}} le {!! $article->created_at->format('d-m-Y') !!}
            </em>
        </section>
    </div>
</article>
<br>
@endforeach
{!! $links !!}
@endsection
```

Ajout d'une nouvelle vue pour le formulaire de création d'un nouvel article (`\resources\views\view_ajoute_article.blade.php`) :

```php+HTML
@extends('template')

@section('contenu')
<BR>
<div class="col-sm-offset-3 col-sm-6">
    <div class="panel panel-info">
        <div class="panel-heading">Ajout d'un article</div>
        <div class="panel-body">
            <form method="POST" action="{{route('articles.store')}}" accept-charset="UTF-8">
            @csrf
            <div class="form-group {!! $errors->has('titre') ? 'has-error' : '' !!}">
                <input class="form-control" placeholder="Titre" name="titre" type="text">
                {!! $errors->first('titre', '<small class="help-block">:message</small>') !!}
            </div>
            <div class="form-group {!! $errors->has('contenu') ? 'has-error' : '' !!}">
                <textarea class="form-control" placeholder="Contenu" name="contenu" cols="50" rows="10"></textarea>
                {!! $errors->first('contenu', '<small class="help-block">:message</small>') !!}
            </div>
            <input class="btn btn-info pull-right" type="submit" value="Envoyer">
            </form>
        </div>
    </div>
    <a href="javascript:history.back()" class="btn btn-primary"><span class="glyphicon glyphicon-circle-arrow-left"></span>Retour</a>
</div>
@endsection
```

Classe de validation des champs du formulaire de création d'un nouvel article :

```
php artisan make:request ArticleRequest
```

Mise à jour du fichier de validation (`app\Http\Requests\ArticleRequest`) : 

```php+HTML
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'titre'=>'required|max:80',
            'contenu'=>'required'
        ];
    }
}
```

Il ne reste plus qu'à gérer la déconnexion (`logout`).

Ajoutons une nouvelle route et sa méthode associée (que nous allons ajouter)

```php
Route::get('logout', [LoginController::class, 'logout']);
```

> Remarques : 
>
> - Lors de la mise en place de l'authentification deux nouvelles routes ont été ajoutée.
>
> ```php
> Auth::routes();
> Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
> ```
>
> - Ne pas oublier le `use` de la classe `App\Http\Controllers\Auth\LoginController`

Ajoutons maintenant la méthode `logout()` dans le contrôleur (`\app\Http\Controllers\Auth\LoginController.php`) sans oublier le `use` pour la classe `Auth.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
//use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = "/articles";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function logout() {
        Auth::logout();
        return redirect('/articles');
    }
}
```

Voilà notre application est fonctionnelle :slightly_smiling_face:

> Remarques : 
>
> Pour voir les différentes possibilités à œuvre, il est nécessaire de se connecter avec différents rôles :
>
> - admin
> - utilisateur normal
>
> Comme les rôles ont été attribués aléatoirement lors du `seed`, il faut aller voir dans la table `users` à l'aide de `tinker` ou de `DB Browser for SQLite`

Voici un résumé de ce que nous avons appris aujourd'hui :

- Nous savons comment ajouter rapidement des enregistrement dans une base de donnée grâce aux `Seeders`.
- Nous savons implémenter une relation 1:N dans Laravel à l'aide des méthodes :
  - `->belongsTo(...)`
  - `->hasMany(...)`
- Nous savons mettre en place une authentification.
- Nous avons mis en place un `middleware` pour pouvoir accéder à différentes fonctionnalités (si on est admin ou pas)
- Nous savons comment rediriger l'utilisateur après son authentification et lors de sa déconnexion.
