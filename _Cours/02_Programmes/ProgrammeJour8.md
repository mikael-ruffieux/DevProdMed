# Programme Jour 8 (Relation n:n)

[Documentation officielle concernant toutes les relations possibles](https://laravel.com/docs/8.x/eloquent-relationships)

Lors du dernier cours nous avons appris à gérer la relation dite 1:n. 
Aujourd'hui nous allons nous occuper de la relation dite `n:n` (`many to many`) qui nécessite une table dédiée (table pivot).

La relation `n:n` consiste à avoir : 

- un enregistrement d'une table T1 pouvant être en relation avec plusieurs enregistrements d'une table T2

 - un enregistrement d'une table T2 pouvant être en relation avec plusieurs enregistrements d'une table T1

Nous allons poursuivre l'exemple du cours précédant qui consistait à avoir des personnes qui rédigent des articles,
en ajoutant la possibilité d'ajouter des mots-clés aux articles.

 - un article peut avoir plusieurs mots-clés.
 - un mot-clé peut être attaché à plusieurs articles.

C'est ce que l'on nomme une relation `n:n`

Tables mises		
en place lors		                     Tables à mettre
du dernier cours		                     en place
──────────	                   	aujourd'hui
    |			 |	               	    ────────────
    |             |                               |                     |
    |             |                        (table pivot)          |
`users`    `articles`         `article_motcle` `motcles`

Pour ne pas tout refaire depuis le début, nous allons dupliquer le projet réalisé lors du dernier cours et nous ajouterons les fichiers et le code nécessaire pour la gestion des mots-clés.

> Créons une nouvelle base de données et modifions le fichier `.env` pour que Laravel puisse s'y connecter.
>
> Dans le cas de l'utilisation de `Sqlite`, il faut :
>
> - ouvrir le fichier `database.sqlite` avec un éditeur de texte, 
>
> - sélectionner tout le texte et le supprimer. 
>
> - Ne pas oublier de sauver
>
> - Quitter l'éditeur de texte.
>
> - Mettre à jour le chemin de la base de données dans le fichier `.env`
>
>   ```
>  DB_DATABASE=... ...\app_un_n\laravel\database\database.sqlite
>   ```
> 
>   en
>
>   ```
>  DB_DATABASE=... ...\app_n_n\laravel\database\database.sqlite
>   ```
> 

Pour tester que tout est fonctionnel, que devons nous faire ?

> Voici la marche à suivre :
>
> - Créer la table `migrations` nécessaire au bon fonctionnement d'`Eloquent`.
> - Faire la migration des tables `users` et `articles`
> - Faire le peuplement de nos deux tables `users` et `articles`
> - Contrôler que nos données soient bien présentes dans les tables à l'aide de `tinker`
>

Commençons par créer la table `migrations`:

```
php artisan migrate:install
```

Créons maintenant les tables `users` et `articles`

```
php artisan migrate
```

Passons maintenant au peuplement des tables `users` et `articles`

```
php artisan db:seed
```

Lançons `tinker` pour voir si nos données sont présentes :

```
 php artisan tinker
```

Pour voir les trois premiers articles

```
>>> App\Models\Article::limit(3)->get();
```

 Ce qui nous retourne :

```
=> Illuminate\Database\Eloquent\Collection {#4317                                                
     all: [                                                                                      
       App\Models\Article {#4318                                                                 
         id: "1",                                                                                
         created_at: "2020-08-29 07:00:59",                                                      
         updated_at: "2020-08-29 07:00:59",                                                      
         titre: "Titre1",                                                                        
         contenu: "Contenu 1 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel a
uctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cras e
u massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",          
         user_id: "4",                                                                           
       },                                                                                        
       App\Models\Article {#4319                                                                 
         id: "2",                                                                                
         created_at: "2020-02-25 07:00:59",                                                      
         updated_at: "2020-02-25 07:00:59",                                                      
         titre: "Titre2",                                                                        
         contenu: "Contenu 2 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel a
uctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cras e
u massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",          
         user_id: "8",                                                                           
       },                                                                                        
       App\Models\Article {#4320                                                                 
         id: "3",                                                                                
         created_at: "2015-04-08 07:00:59",                                                      
         updated_at: "2015-04-08 07:00:59",                                                      
         titre: "Titre3",                                                                        
         contenu: "Contenu 3 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel a
uctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cras e
u massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",          
         user_id: "4",                                                                           
       },                                                                                        
     ],                                                                                          
   }
```

Créons maintenant le fichier qui permettra la création de la table `motcles` à l'aide de la commande :

```
php artisan make:migration create_motcles_table
```

Ajoutons le code nécessaire au typage et au nommage des champs :

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motcles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('mot', 50)->unique();
            $table->string('mot_url', 60)->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motcles');
    }
}
```

> Remarque : 
> Le champ mot_url permettra la recherche par mot clé (que l'on devra transmettre dans une url).
> Comme l'utilisateur peut entrer des caractères spéciaux (apostrophe par ex.), nous convertirons ces caractères pour qu'ils puissent êtres adaptés aux `urls`.

## Remarque importante pour le nommage de la table pivot

Par convention, le nom de la table pivot comprend les noms des deux tables en relation mais au 
**SINGULIER** et par **ORDRE ALPHABETIQUE** !

Voici comment procéder obtenir le nom de la table pivot :

La première table est `articles`, le singulier de `articles` est `article`.
La seconde table est `motcles`, le singulier de `motcles` est `motcle`
`article` vient avant `motcle` (ordre alphabétique)
donc le nom de la table pivot sera `article_motcle`

Créons le fichier qui permettra la création de la table pivot `article_motcle` à l'aide de la commande :

```
php artisan make:migration create_article_motcle_table
```

Et voici son contenu :

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleMotcleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('article_motcle', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('article_id')->unsigned();
            $table->integer('motcle_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('articles')
                    ->onDelete('restrict')
                    ->onUpdate('restrict');
            $table->foreign('motcle_id')->references('id')->on('motcles')
                    ->onDelete('restrict')
                    ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('article_motcle', function (Blueprint $table) {
                $table->dropForeign(['article_id']);
                $table->dropForeign(['motcle_id']);
            });
        }
        Schema::dropIfExists('article_motcle');
    }
}
```

Nous pouvons maintenant créer ces deux tables à l'aide de la commande :

```
php artisan migrate
```

```
Migrating: 2021_04_19_070226_create_motcles_table                                                
Migrated:  2021_04_19_070226_create_motcles_table (16.27ms)                                      
Migrating: 2021_04_19_070348_create_article_motcle_table                                         
Migrated:  2021_04_19_070348_create_article_motcle_table (5.26ms)
```

Les tables (`users` et `articles`) ne sont pas impliquées par la migration (car elles existent déjà).

> Remarque : Pour ne pas obtenir d'erreurs lors de la migration, il faut que la création des tables soient effectués dans le bon ordre. L'ordre des migrations est déterminé par la date (dans le nom du fichier migration).
> Si vous n'avez pas respecté l'ordre, il suffit de changer la date dans le nom des migrations. :-)

Passons maintenant au peuplement des tables `motcles` et `article_motcle`.

> (De la même manière que nous avions fait lors du dernier cours pour les tables `users` et `articles`)

Créons un fichier `MotclesTableSeeder.php` dans le répertoire `\database\seeds` : 

```
php artisan make:seeder MotclesTableSeeder
```

Et complétons le :

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class MotclesTableSeeder extends Seeder
{
    private function randDate() {
        return Carbon::createFromDate(null, rand(1, 12), rand(1, 28));
    }

    public function run() {
        DB::table('motcles')->delete();
        for ($i = 1; $i <= 20; $i++) {
            $date = $this->randDate();
            DB::table('motcles')->insert([
                'mot' => 'mot' . $i,
                'mot_url' => 'mot' . $i,
                'created_at' => $date,
                'updated_at' => $date]
            );
        }
    }
}
```

Nous obtiendrons de cette manière vingt mots-clés (mot1, mot2, ..., mot20)

Créons maintenant un nouveau fichier permettant le peuplement de la table `article_motcle` et complétons-le :

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ArticleMotcleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('article_motcle')->delete();
        for ($i = 1; $i <= 100; $i++) {
            $numbers = range(1, 20);
            shuffle($numbers);
            $n = rand(3, 6);
            for ($j = 1; $j <= $n; $j++) {
                DB::table('article_motcle')->insert([
                    'article_id' => $i,
                    'motcle_id' => $numbers[$j]]
                );
            }
        }
    }
}
```

Ce code ajoute aléatoirement au minimum 3 et au maximum 6 mots-clés aux 100 articles de notre base de données.

> Remarque : Il y a 20 mots-clés dans notre base de données

Pour s'assurer que Laravel tienne compte de tous les fichiers que nous venons de créer, lançons la commande :

```
composer dumpautoload
```

Nous avons vu lors du dernier cours qu'il fallait renseigner un fichier pour que les peuplements de nos tables se fassent dans le bon ordre. Il s'agit du fichier : `DatabaseSeeder.php` qui se trouve dans le même répertoire que les `seeders` que nous venons de créer.

Editons ce fichier pour le mettre à jour :

```php
<?php

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
        //$this->call(UsersTableSeeder::class);              // <-----
        //$this->call(ArticlesTableSeeder::class);           // <-----
        $this->call(MotclesTableSeeder::class);              // <-----
        $this->call(ArticleMotcleTableSeeder::class);        // <-----
    }
}
```

> Puisque les données existent déjà dans les tables `users` et `articles`, il faut commenter les deux premières lignes !
>

Peuplons nos deux tables (`motcles` et `article_motcle`) à l'aide de la commande :

```
php artisan db:seed
```

> Si pour une raison ou une autre vous aimeriez supprimer les tables dans la base de donnée, voici les commandes SQL nécessaires (à faire dans cet ordre ;-)
>
> - ```
>   drop table article_motcle;
>   ```
>
> - ```
>   drop table motcles;
>   ```
>
> - ```
>   drop table articles;
>   ```
>
> - ```
>   drop table users;
>   ```
>
> - ```
>   drop table migrations;
>   ```
>
> Remarque : Pour lancer la création des tables et leur peuplement simultanément, il existe la commande : 
>
> ```
> php artisan migrate --seed
> ```

## Mise en place de la relation `n:n` pour `Eloquent`

La relation `n:n` entre les articles et les mot-clés se définit dans deux classes `modèles`.

La classe modèle `Article.php` existe déjà (nous l'avons crée lors du dernier cours)
Il nous faut créer la nouvelle classe modèle : `Motcle.php` et ajoutons la relation qui indique qu'un mot-clé peut être référencé par plusieurs articles.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motcle extends Model
{
    use HasFactory;
    
    protected $fillable=['mot','mot_url'];
    
    public function articles() {
        return $this->belongsToMany(Article::class); // chaque mot-clé peut être référencé par
                                                     // plusieurs articles
    }
}
```

Il nous reste encore à indiquer qu'un article peut avoir des mots-clés.

Edition le fichier `Article.php` existant et ajoutons cette relation :

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    
    protected $fillable=['titre','contenu','user_id'];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    // Relation n:n entre un article et les mots-clés
    public function motcles() {
        return $this->belongsToMany(Motcle::class);
    }
}
```

Pour que Laravel tienne compte de notre nouvelle classe modèle : 

```
composer dumpautoload
```

Lançons `tinker` pour contrôler que tout fonctionne :

```
php artisan tinker
```

Pour visualiser les trois premiers mots-clés :

```
>>>  App\Models\MotCle::limit(3)->get()
```

Qui nous retourne :

```
=> Illuminate\Database\Eloquent\Collection {#4317                                                
     all: [                                                                                      
       App\Models\Motcle {#4318                                                                  
         id: "1",                                                                                
         created_at: "2021-11-10 07:16:22",                                                      
         updated_at: "2021-11-10 07:16:22",                                                      
         mot: "mot1",                                                                            
         mot_url: "mot1",                                                                        
       },                                                                                        
       App\Models\Motcle {#4319                                                                  
         id: "2",                                                                                
         created_at: "2021-02-07 07:16:22",                                                      
         updated_at: "2021-02-07 07:16:22",                                                      
         mot: "mot2",                                                                            
         mot_url: "mot2",                                                                        
       },                                                                                        
       App\Models\Motcle {#4320                                                                  
         id: "3",                                                                                
         created_at: "2021-07-06 07:16:22",                                                      
         updated_at: "2021-07-06 07:16:22",                                                      
         mot: "mot3",                                                                            
         mot_url: "mot3",                                                                        
       },                                                                                        
     ],                                                                                          
   }
```

Pour contrôler que notre relation `n:n` fonctionne bien, nous allons tout d'abord demander la liste des mots-clés de l'article ayant l'identifiant : 1

```
>>> App\Models\Article::findOrFail(1)->motcles()->get()
```

```
=> Illuminate\Database\Eloquent\Collection {#4319                                                
     all: [                                                                                      
       App\Models\Motcle {#4324                                                                  
         id: "12",                                                                               
         created_at: "2021-04-15 07:16:22",                                                      
         updated_at: "2021-04-15 07:16:22",                                                      
         mot: "mot12",                                                                           
         mot_url: "mot12",                                                                       
         pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4321                              
           article_id: "1",                                                                      
           motcle_id: "12",                                                                      
         },                                                                                      
       },                                                                                        
       App\Models\Motcle {#4325                                                                  
         id: "19",                                                                               
         created_at: "2021-10-19 07:16:22",                                                      
         updated_at: "2021-10-19 07:16:22",                                                      
         mot: "mot19",                                                                           
         mot_url: "mot19",                                                                       
         pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4323                              
           article_id: "1",                                                                      
           motcle_id: "19",                                                                      
         },                                                                                      
       },                                                                                        
       App\Models\Motcle {#4326                                                                  
         id: "10",                                                                               
         created_at: "2021-02-20 07:16:22",                                                      
         updated_at: "2021-02-20 07:16:22",                                                      
         mot: "mot10",                                                                           
         mot_url: "mot10",                                                                       
         pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4316                              
           article_id: "1",                                                                      
           motcle_id: "10",                                                                      
         },                                                                                      
       },                                                                                        
       App\Models\Motcle {#4327                                                                  
         id: "13",                                                                               
         created_at: "2021-07-05 07:16:22",                                                      
         updated_at: "2021-07-05 07:16:22",                                                      
         mot: "mot13",                                                                           
         mot_url: "mot13",                                                                       
         pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4317                              
           article_id: "1",                                                                      
           motcle_id: "13",                                                                      
         },                                                                                      
       },                                                                                        
       App\Models\Motcle {#4328                                                                  
         id: "14",                                                                               
         created_at: "2021-02-19 07:16:22",                                                      
         updated_at: "2021-02-19 07:16:22",                                                      
         mot: "mot14",                                                                           
         mot_url: "mot14",                                                                       
         pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4298                              
           article_id: "1",                                                                      
           motcle_id: "14",                                                                      
         },                                                                                      
       },                                                                                        
     ],                                                                                          
   }
```

Testons maintenant que la relation fonctionne aussi dans l'autre sens.
Nous allons demander la liste des trois premiers articles contenant le mot-clé ayant l'identifiant : 1

```
App\Models\Motcle::findOrFail(1)->articles()->limit(3)->get()
```

```
=> Illuminate\Database\Eloquent\Collection {#4319                                                
     all: [                                                                                      
       App\Models\Article {#4320                                                                 
         id: "2",                                                                                
         created_at: "2020-02-25 07:00:59",                                                      
         updated_at: "2020-02-25 07:00:59",                                                      
         titre: "Titre2",                                                                        
         contenu: "Contenu 2 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel a
uctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cras e
u massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",          
         user_id: "8",                                                                           
         pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4323                              
           motcle_id: "1",                                                                       
           article_id: "2",                                                                      
         },                                                                                      
       },                                                                                        
       App\Models\Article {#4321                                                                 
         id: "3",                                                                                
         created_at: "2015-04-08 07:00:59",                                                      
         updated_at: "2015-04-08 07:00:59",                                                      
         titre: "Titre3",                                                                        
         contenu: "Contenu 3 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel a
uctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cras e
u massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",          
         user_id: "4",                                                                           
         pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4317                              
           motcle_id: "1",                                                                       
           article_id: "3",                                                                      
         },                                                                                      
       },                                                                                        
       App\Models\Article {#4324                                                                 
         id: "6",                                                                                
         created_at: "2021-01-14 07:00:59",                                                      
         updated_at: "2021-01-14 07:00:59",                                                      
         titre: "Titre6",                                                                        
         contenu: "Contenu 6 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel a
uctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cras e
u massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.",          
         user_id: "2",                                                                           
         pivot: Illuminate\Database\Eloquent\Relations\Pivot {#4309                              
           motcle_id: "1",                                                                       
           article_id: "6",                                                                      
         },                                                                                      
       },                                                                                        
     ],                                                                                          
   }
```

Maintenant que tout fonctionne au niveau des données, nous pouvons peaufiner l'interface utilisateur de notre application et ajouter la gestion des mots-clés.

Les mots-clés vont être entrés dans un champ texte et devront être séparés par des virgules (pas d'espaces entre les mots-clés).
Malgré le grand nombre de validations offertes par défaut, Laravel ne dispose pas d'une telle règle. 
Nous allons donc créer une règle de validation personnalisée pour ce cas.

Editons le fichier `ArticleRequest.php` que nous avions créé lors du dernier cours et ajoutons notre nouvelle règle à l'aide d'une expression régulière :

```php
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
            'contenu'=>'required',
            // ajout de l'expression régulière de validation des mots-clés
            'motcles' => ['Regex:/^[A-Za-z0-9-àéèêëïôùû]{1,50}?(,[A-Za-z0-9-àéèêëïôùû]{1,50})*$/']
        ];
    }
}
```

Il nous faut aussi personnaliser le message d'erreur correspondant dans le fichier `\resources\lang\en\validation.php`
Aux alentours de la ligne 136, nous trouvons les lignes suivantes :

```php
'custom' => [
     'attribute-name' => [
         'rule-name' => 'custom-message',
     ]   
],
```

Cette section nous permet d'ajouter des message personnalisés. Modifions cette section de la manière suivante :

```php
'custom' => [
    'motcles' => [
        'regex' => 'tags, separated by commas (no spaces), should have a maximum of 50 characters',
    ],
],
```

Nous pouvons maintenant passer à la modification de notre contrôleur :

Voici son contenu tel que nous l'avons laissé lors du dernier cours (sans la gestion des mot-clés) :

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;

class ArticleController extends Controller
{
    protected $nbArticlesParPage = 4;
    
    public function __construct() {
        $this->middleware('auth', ['except'=>'index']);
        $this->middleware('admin', ['only'=>'destroy']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles=Article::with('user')
                ->orderBy('articles.created_at','desc')
                ->paginate($this->nbArticlesParPage);
        $links=$articles->render();
        return view('view_articles', compact('articles','links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('view_ajoute_article');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
       $inputs=array_merge($request->all(), ['user_id'=>$request->user()->id]);
       Article::create($inputs);
       return redirect(route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Article::findOrFail($id)->delete();  
        return redirect()->back();
    }
}
```

Adaptons le code pour que celui-ci prenne en compte la gestion des mots-clés :

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Motcle;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    protected $nbArticlesParPage = 4;
    
    public function __construct() {
        // pour tester la méthode 'articleAyantMotcle' sans authentification
        // $this->middleware('auth', ['except' => ['index', 'articlesAyantMotcle']]);
        $this->middleware('auth', ['except'=>'index']);
        $this->middleware('admin', ['only'=>'destroy']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles=Article::with('user')
                ->orderBy('articles.created_at','desc')
                ->paginate($this->nbArticlesParPage);
        $links=$articles->render();
        return view('view_articles', compact('articles','links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('view_ajoute_article');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
       $inputs = array_merge($request->all(), ['user_id' => $request->user()->id]);
        $article = Article::create($inputs);
        if (isset($inputs['motcles'])) {
            $tabMotcles = explode(',', $inputs['motcles']);
            foreach ($tabMotcles as $motcle) {
                // trim(...) enlève les espaces superflux en début et en fin de chaîne
                $motcle = trim($motcle);
                // Str::slug génère une nouvelle chaîne similaire à $motcle mais adaptée aus urls
                // adaptation des caractères accentués et/ou caractères spéciaux
                $mot_url = Str::slug($motcle);
                $mot_ref = Motcle::where('mot_url', $mot_url)->first();
                if (is_null($mot_ref)) {
                    $mot_ref = new Motcle([
                        'mot' => $motcle,
                        'mot_url' => $mot_url
                    ]);
                    $article->motcles()->save($mot_ref);
                } else {
                    $article->motcles()->attach($mot_ref->id);
                }
            }
        }
        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->motcles()->detach();
        $article->delete();
        return redirect()->back();
    }
    
    public function articlesAyantMotcle($motcle) {
        $articles = Article::with('user', 'motcles')
                        ->orderBy('articles.created_at', 'desc')
                        ->whereHas('motcles', function($q) use ($motcle) {
                            $q->where('motcles.mot_url', $motcle);
                        })->paginate($this->nbArticlesParPage);
        //return $articles;  // pour tester rapidement que la méthode fonctionne
        $links=$articles->render();
        return view('view_articles', compact('articles','links'))
                ->with('info', 'Résultats pour la recherche du mot-clé : ' . $motcle);
    } 
}
```

Comme nous avons ajouté une méthode `articlesAyantMotcle(...)` permettant la recherche des articles qui comportent le mot-clé passé en paramètre, il faut ajouter une nouvelle route dans le fichier `web.php`

```
Route::get('articles/motcle/{motcle}', [ArticleController::class, 'articlesAyantMotcle']);
```

> Remarque : Pour tester rapidement la méthode `articlesAyantMotCle(...)`, nous pouvons modifier le constructeur du contrôleur pour ne pas avoir à s'authentifier pour pouvoir appeler cette méthode.
>
> ```
>  ...
>  $this->middleware('auth', ['except' => ['index','articlesAyantMotcle']]);
>  ...
> ```
>
> Pour contrôler l'effet de cette modification, nous pouvons lancer la commande :
>
> ```
> php artisan route:list
> ```
>
> En observant le résultat, nous pouvons voir que l'authentification n'est plus nécessaire pour cette route
>
> ```
> URI : articles/motcle/{motcle}  
> Action : App\Http\Controllers\ArticleController@articlesAyantMotcle
> middleware : web   => (auth a disparu :-)
> ```
>
> Comme notre vue n'a pas encore été adaptée aux mots-clés, nous devons modifier la méthode `articlesAyantMotCle(...)` pour court-circuiter l'appel à la vue :
>
> ```php
> public function articlesAyantMotcle($motcle) {
>    $articles = Article::with('user', 'motcles')
>                     ->orderBy('articles.created_at', 'desc')
>                     ->whereHas('motcles', function($q) use ($motcle) {
>                         $q->where('motcles.mot_url', $motcle);
>                     })->paginate($this->nbArticlesParPage);
>     return $articles;
>     //$links=$articles->render();
>     //return view('view_liste_articles', compact('articles','links'))
>     //        ->with('info', 'Résultats pour la recherche du mot-clé : ' . $motcle);
> }
> ```
>
> Nous pouvons tester maintenant le méthode en appelant l'url suivante :
>
> ```
> http://localhost:8000/articles/motcle/mot1
> ```
>
> Ce qui retourne :
>
> ```
> {"current_page":1,"data":[{"id":82,"created_at":"2021-03-07T07:00:59.000000Z","updated_at":"2021-03-07T07:00:59.000000Z","titre":"Titre82","contenu":"Contenu 82 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.","user_id":"9","user":{"id":9,"name":"Nom9","email":"email9@gmx.ch","email_verified_at":null,"admin":"0","created_at":null,"updated_at":null},"motcles":[{"id":1,"created_at":"2021-11-10T07:16:22.000000Z","updated_at":"2021-11-10T07:16:22.000000Z","mot":"mot1","mot_url":"mot1","pivot":{"article_id":"82","motcle_id":"1"}},{"id":14,"created_at":"2021-02-19T07:16:22.000000Z","updated_at":"2021-02-19T07:16:22.000000Z","mot":"mot14","mot_url":"mot14","pivot":{"article_id":"82","motcle_id":"14"}},{"id":20,"created_at":"2021-07-22T07:16:22.000000Z","updated_at":"2021-07-22T07:16:22.000000Z","mot":"mot20","mot_url":"mot20","pivot":{"article_id":"82","motcle_id":"20"}},{"id":8,"created_at":"2021-01-05T07:16:22.000000Z","updated_at":"2021-01-05T07:16:22.000000Z","mot":"mot8","mot_url":"mot8","pivot":{"article_id":"82","motcle_id":"8"}},{"id":19,"created_at":"2021-10-19T07:16:22.000000Z","updated_at":"2021-10-19T07:16:22.000000Z","mot":"mot19","mot_url":"mot19","pivot":{"article_id":"82","motcle_id":"19"}}]},{"id":6,"created_at":"2021-01-14T07:00:59.000000Z","updated_at":"2021-01-14T07:00:59.000000Z","titre":"Titre6","contenu":"Contenu 6 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.","user_id":"2","user":{"id":2,"name":"Nom2","email":"email2@gmx.ch","email_verified_at":null,"admin":"0","created_at":null,"updated_at":null},"motcles":[{"id":12,"created_at":"2021-04-15T07:16:22.000000Z","updated_at":"2021-04-15T07:16:22.000000Z","mot":"mot12","mot_url":"mot12","pivot":{"article_id":"6","motcle_id":"12"}},{"id":7,"created_at":"2021-03-21T07:16:22.000000Z","updated_at":"2021-03-21T07:16:22.000000Z","mot":"mot7","mot_url":"mot7","pivot":{"article_id":"6","motcle_id":"7"}},{"id":4,"created_at":"2021-03-11T07:16:22.000000Z","updated_at":"2021-03-11T07:16:22.000000Z","mot":"mot4","mot_url":"mot4","pivot":{"article_id":"6","motcle_id":"4"}},{"id":1,"created_at":"2021-11-10T07:16:22.000000Z","updated_at":"2021-11-10T07:16:22.000000Z","mot":"mot1","mot_url":"mot1","pivot":{"article_id":"6","motcle_id":"1"}},{"id":15,"created_at":"2021-01-17T07:16:22.000000Z","updated_at":"2021-01-17T07:16:22.000000Z","mot":"mot15","mot_url":"mot15","pivot":{"article_id":"6","motcle_id":"15"}},{"id":13,"created_at":"2021-07-05T07:16:22.000000Z","updated_at":"2021-07-05T07:16:22.000000Z","mot":"mot13","mot_url":"mot13","pivot":{"article_id":"6","motcle_id":"13"}}]},{"id":29,"created_at":"2020-10-31T07:00:59.000000Z","updated_at":"2020-10-31T07:00:59.000000Z","titre":"Titre29","contenu":"Contenu 29 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.","user_id":"4","user":{"id":4,"name":"Nom4","email":"email4@gmx.ch","email_verified_at":null,"admin":"1","created_at":null,"updated_at":null},"motcles":[{"id":20,"created_at":"2021-07-22T07:16:22.000000Z","updated_at":"2021-07-22T07:16:22.000000Z","mot":"mot20","mot_url":"mot20","pivot":{"article_id":"29","motcle_id":"20"}},{"id":1,"created_at":"2021-11-10T07:16:22.000000Z","updated_at":"2021-11-10T07:16:22.000000Z","mot":"mot1","mot_url":"mot1","pivot":{"article_id":"29","motcle_id":"1"}},{"id":11,"created_at":"2021-02-27T07:16:22.000000Z","updated_at":"2021-02-27T07:16:22.000000Z","mot":"mot11","mot_url":"mot11","pivot":{"article_id":"29","motcle_id":"11"}},{"id":8,"created_at":"2021-01-05T07:16:22.000000Z","updated_at":"2021-01-05T07:16:22.000000Z","mot":"mot8","mot_url":"mot8","pivot":{"article_id":"29","motcle_id":"8"}},{"id":7,"created_at":"2021-03-21T07:16:22.000000Z","updated_at":"2021-03-21T07:16:22.000000Z","mot":"mot7","mot_url":"mot7","pivot":{"article_id":"29","motcle_id":"7"}}]},{"id":55,"created_at":"2020-10-04T07:00:59.000000Z","updated_at":"2020-10-04T07:00:59.000000Z","titre":"Titre55","contenu":"Contenu 55 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel auctor libero, quis venenatis augue. Curabitur a pulvinar tortor, vitae condimentum libero. Cras eu massa sed lorem mattis lacinia. Vestibulum id feugiat turpis. Proin a lorem ligula.","user_id":"10","user":{"id":10,"name":"Nom10","email":"email10@gmx.ch","email_verified_at":null,"admin":"1","created_at":null,"updated_at":null},"motcles":[{"id":1,"created_at":"2021-11-10T07:16:22.000000Z","updated_at":"2021-11-10T07:16:22.000000Z","mot":"mot1","mot_url":"mot1","pivot":{"article_id":"55","motcle_id":"1"}},{"id":5,"created_at":"2021-04-24T07:16:22.000000Z","updated_at":"2021-04-24T07:16:22.000000Z","mot":"mot5","mot_url":"mot5","pivot":{"article_id":"55","motcle_id":"5"}},{"id":12,"created_at":"2021-04-15T07:16:22.000000Z","updated_at":"2021-04-15T07:16:22.000000Z","mot":"mot12","mot_url":"mot12","pivot":{"article_id":"55","motcle_id":"12"}},{"id":17,"created_at":"2021-11-08T07:16:22.000000Z","updated_at":"2021-11-08T07:16:22.000000Z","mot":"mot17","mot_url":"mot17","pivot":{"article_id":"55","motcle_id":"17"}}]}],"first_page_url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=1","from":1,"last_page":8,"last_page_url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=8","links":[{"url":null,"label":"&laquo; Previous","active":false},{"url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=1","label":"1","active":true},{"url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=2","label":"2","active":false},{"url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=3","label":"3","active":false},{"url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=4","label":"4","active":false},{"url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=5","label":"5","active":false},{"url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=6","label":"6","active":false},{"url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=7","label":"7","active":false},{"url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=8","label":"8","active":false},{"url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=2","label":"Next &raquo;","active":false}],"next_page_url":"http:\/\/localhost:8000\/articles\/motcle\/mot1?page=2","path":"http:\/\/localhost:8000\/articles\/motcle\/mot1","per_page":4,"prev_page_url":null,"to":4,"total":32}
> ```
>
> Preuve qu'elle fonctionne :slightly_smiling_face:
>
> Nous pouvons tout remettre en état (constructeur et méthode `articlesAyantMotcle(...)`)

Nous pouvons passer à la mise à jour de la vue permettant l'affichage des articles.
Il faut ajouter l'implémentation de l'affichage des mots-clés de chaque article.
(La seule chose à modifier est la balise `<header>...</header>` de la section `@section('contenu')` `\resources\views\view_articles.blade.php`

```php+HTML
@extends('template')

@section('header')
@if(Auth::check())
<div class="btn-group pull-right">
    <a href="{{route('articles.create')}}" class="btn btn-info">Cr&eacute;er un article</a>
    <a href="{{url("logout")}}" class="btn btn-warning">Deconnexion</a>
</div>
@else 
<a href="{{url("login")}}" class="btn btn-info pull-right">Se connecter</a>
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
            <div class="pull-right">
                @foreach($article->motcles as $motcle)
                <a href="{{url('articles/motcle', [$motcle->mot_url])}}" class="btn btn-xs btn-info">{{$motcle->mot}}</a>
                @endforeach
            </div>
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
        </hr>
    </div>
</article>
<br>
@endforeach
{!! $links !!}
@endsection
```

Pour pouvoir visualiser l'effet de nos modifications, il suffit de taper l'url suivante  :

```
http://localhost:8000/articles
```

![ListeArticlesAvecMotsCles](img\ListeArticlesAvecMotsCles.png)

Yes !, les mots-clés s'affichent !

Il reste encore l'implémentation de l'ajout de mot(s)-clé(s) lors de la création d'un nouvel article.

Ajoutons un champ texte permettant la saisie des mots-clés dans la vue `\resources\views\view_ajoute_article.blade.php`

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
            <div class="form-group {!! $errors->has('motcles') ? 'has-error' : '' !!}">
                <input class="form-control" placeholder="Entrez les mots-clés spérarés par des virgules (pas d'espaces)" name="motcles" type="text">
                {!! $errors->first('motcles', '<small class="help-block">:message</small>') !!}
            </div>
            <input class="btn btn-info pull-right" type="submit" value="Envoyer">
            </form>
        </div>
    </div>
    <a href="javascript:history.back()" class="btn btn-primary"><span class="glyphicon glyphicon-circle-arrow-left"></span>Retour</a>
</div>
@endsection
```

Nous avons terminé les adaptations de notre code.

Pour pouvoir tester les nouvelles fonctionnalités de notre application, il suffit de s'authentifier :slightly_smiling_face:

Nous savons maintenant comment implémenter un relation n:n dans Laravel.