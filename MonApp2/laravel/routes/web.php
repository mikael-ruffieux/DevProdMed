<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonPremierControleur;
use App\Http\Controllers\ArtistesControleur;
use App\Http\Controllers\ProverbesControleur;

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

Route::get('/', [MonPremierControleur::class, 'maMethodeDansControleur']);

Route::get('article/{n}/couleur/{c}', 'App\Http\Controllers\MonPremierControleur@test')
    ->where(['n'=>'[0-9]+', 'c'=>'rouge|vert|bleu']);

Route::get('artistes/{lettre}', [ArtistesControleur::class, 'afficheArtistes'])
    ->where(['l' => '[a-z]']);

Route::get('afficheImage', 'App\Http\Controllers\MonPremierControleur@afficheImage');

Route::get('mes10Proverbes', [ProverbesControleur::class, 'affiche10Proverbes']);