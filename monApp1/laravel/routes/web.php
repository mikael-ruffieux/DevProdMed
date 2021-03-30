<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Lancer l'app : aller à la racine : php artisan serve

Route::get('/', function () {
    return view('welcome');
});

Route::get('page1/{param1}/{param2}', function ($param1, $param2) {

    $i = [1, 2, 3, 4, 5];
    echo $param1."<br>";
    echo $param2;

})->where('param1', 'article[0-9][0-9]*'); // Règle, on applique cette route que si param1 = ...

// Route nommée
Route::get('/jean/michel', function () {
    echo "Jean-Michel";
})->name("jeanmichel");

// Redirection vers une route nommée
Route::get('/jeanmimi', function () {
    return redirect()->route("jeanmichel");
});

// Exercice 1
Route::get('livret/{n}', function ($n) {
    for ($i=1; $i <= 12; $i++) { 
        echo "$i * $n = ".$i*$n."<br>";
    }
})->where('n', '[2-9]|1[0-2]');

// Exercice 2
Route::get('{nom}', function ($nom) {
    echo "page 1";
})->where('nom', '[pP]age1');

// Exercice 3
/*
url : .../cff/Lausanne/8:30/Yverdon       
=> redirige vers  
https://www.sbb.ch/fr/acheter/pages/fahrplan/fahrplan.xhtml?von=Lausanne&nach=Yverdon&datum=23.02.2021&zeit=08:30&suche=true
*/

Route::get('cff/{depart}/{heure}/{destination}/{date?}', function ($depart, $heure, $destination, $date = null) {
    
    $url = "https://www.sbb.ch/fr/acheter/pages/fahrplan/fahrplan.xhtml?"
    ."von=$depart"
    ."&nach=$destination"
    ."&datum=$date"
    ."&zeit=$heure"
    ."&suche=true";

    echo $url;
    //return redirect($url);
});