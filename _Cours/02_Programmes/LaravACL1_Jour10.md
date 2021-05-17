# ACL - Attribution d'un rôle à un utilisateur.

Hello et bienvenue pour cette partie qui consiste à mettre en œuvre un contrôle d'accès dans Laravel.

> Remarque : Avec cette solution, il n'est possible d'attribuer qu'un seul rôle à un utilisateur !

Grâce à ce que nous allons voir ci-dessous, il sera possible :

- Dans les vues :

  - Afficher des parties dédiées à certains rôles. (Administrateur, modérateur, etc.)

  

- Dans les contrôleurs :

  - Autoriser l'accès aux méthodes qu'à certains rôles.

## Partie 1

Implémenter l'authentification (déjà vu ensemble)

```
composer require laravel/ui
```

```
php artisan ui bootstrap --auth
```

```php
npm install && npm run dev
npm install && npm run dev // une seconde fois pour terminer l'installation ;-)
```

Pour rediriger l'utilisateur après son identification nous pouvons modifier le contrôleur qui a été ajouté lors de la mise en place de l'authentification.

Il s'agit du contrôleur `app\Http\Controllers\Auth\LoginController.php`

Il suffit de changer la ligne 29 :

```
protected $redirectTo = RouteServiceProvider::HOME;
```

en 

```
protected $redirectTo = "/";
```

> si on désire afficher la vue `welcome.blade.php` par exemple.
>

## Partie 2

Ajouter un champ supplémentaire `user_type` dans la "migration" pour pouvoir attribuer un rôle à un utilisateur.

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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('user_type')->default('user');        //<------- ici
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
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

Pour préparer le terrain de création d'un utilisateur via un formulaire, modifier la classe modèle liée à cette table en rajoutant une entrée (`user_type`) dans le tableau de la propriété `$fillable`

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
        'user_type',        // <- ici
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
}
```

Ajouter deux utilisateurs avec des rôles différents pour faire nos tests à l'aide d'un `seeder`

```
php artisan make:seeder UsersTableSeeder
```

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                'name' => 'Nom1',
                'email' => 'email1@gmx.ch',
                'password' => Hash::make('password1'),
                'user_type' => 'Role1']);
        DB::table('users')->insert([
                'name' => 'Nom2',
                'email' => 'email2@gmx.ch',
                'password' => Hash::make('password2'),
                'user_type' => 'Role2']);
    }
}
```

Ajouter le seeder que l'on vient de créer dans le fichier `app\database\seeders\DatabaseSeeder.php`

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
    }
}
```

Créons un fichier `database.sqlite` vide dans le répertoire `app\database\`

Mettons à jour le fichier `.env`

```
...
DB_CONNECTION=sqlite
DB_HOST=
DB_PORT=
DB_DATABASE= ... répertoire où se trouve l'application ...\laravel\database\database.sqlite
DB_USERNAME=
DB_PASSWORD=
...
```

Ajoutons les tables dans la base de données

```
php artisan migrate:install
```

```
php artisan migrate
```

Ajoutons les données dans les tables de la base de données

```
php artisan db:seed
```

## Partie 3 - Informer Laravel de l'existence des rôles

Modifions le fichier (existant) `laravel\app\Providers\AuthServiceProvider.php`
(ajoutons un `use` et modifions la méthode `boot()`)

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
        
        /**
         * Rend true si l'utilisateur a le rôle "Role1"
         */
        $gate->define('isRole1', function($user) {
            return $user->user_type == 'Role1';
        });
        
        /**
         * Rend true si l'utilisateur a le rôle "Role2"
         */
        $gate->define('isRole2', function($user) {
            return $user->user_type == 'Role2';
        });        
    }
}
```



## Partie 4 - Ajout du tag Blade @can dans une vue

Nous allons utiliser un nouveau tag de `Blade` : @can  [voir doc. officielle](https://laravel.com/docs/8.x/authorization#via-blade-templates)

Pour cette partie nous allons simplement modifier la vue existante `welcome.blade.php` et ajouter une partie avant la partie finale de la page :

```html
...
<div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
```

en

```html
<div class="text-center text-sm text-gray-500 sm:text-center">
                        @can('isRole1')
                            Tu as le rôle : Role1
                        @elsecan('isRole2')
                            Tu as le rôle : Role2
                        @else
                            Tu n'a pas encore le rôle Role1 ou Role2
                        @endcan
                    </div>

                    <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
```

Voilà, c'est fait. Nous n'avons plus qu'à tester que tout est fonctionnel :slightly_smiling_face:

Lançons l'application

![Welcome](img\Welcome.png)

 Authentifions nous avec :

```
email1@gmx.ch
password1
```

Ce qui nous donne :

![WelcomeRole1](img\WelcomeRole1.png)

Avec l'autre utilisateur, nous aurions eu :

```
email2@gmx.ch
password2
```

![WelcomeRole2](img\WelcomeRole2.png)

## Partie 5 - Limiter l'accès aux méthodes d'un contrôleur

Créons un contrôleur

```
php artisan make:controller MyController
```

et ajoutons une méthode à notre contrôleur avec le code suivant :

```php
<?php

namespace App\Http\Controllers;

use \Gate;

class MyController extends Controller
{
    public function myMethod() {
        if (!Gate::allows('isRole1')) {
            abort(403,"Tu n'est pas authorisé à faire cette action");
        }
        return "Il n'y a que le rôle 'Role1' qui a le droit à cette méthode";
    }
}
```

puis ajoutons une nouvelle route dans le fichier `\route\web.php`

```php
Route::get('/myMethod', [MyController::class, 'myMethod']);
```

Nous pouvons passer aux tests :slightly_smiling_face:

 Lançons l'application est authentifions nous avec :

```
email1@gmx.ch
password1
```

et entrons l'url suivante :

```
http://localhost:8000/ ... /myMethod
```

Vous pouvons voir :

![MyMethodRole1](img\MyMethodRole1.png)

Avec l'autre utilisateur, nous aurions eu :

```
email2@gmx.ch
password2
```



![MyMethodRole2](img\MyMethodRole2.png)



Voilà, nous sommes au terme de cette partie.

Nous avons vu comment implémenter un contrôle d'accès simple dans Laravel et sommes capables d'ajouter dans notre application des fonctionnalités réservées à certains rôles.