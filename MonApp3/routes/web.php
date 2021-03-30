<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ManifestationController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\VoitureController;
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

Route::get('/', function () {
    return view('welcome');
});

//Route::get('mes10Proverbes', [ProverbesControleur::class, 'affiche10Proverbes']);

// Jour 3 : exercice
Route::get('agenda', [FormController::class, 'afficheForm']);
Route::post('agenda', [FormController::class,'traiteForm']);

// Jour 4 : cours
Route::get('contact', [ContactController::class,'rendFormulaire']);
Route::post('contact', [ContactController::class,'valideEtTraiteFormulaire']);

// Jour 4 : exercice
Route::get('manifestation', [ManifestationController::class,'rendFormulaire']);
Route::post('manifestation', [ManifestationController::class,'valideEtTraiteFormulaire']);

// Jour 5 : cours
Route::get('newsletter', [NewsletterController::class, 'rendFormulaire']);
Route::post('newsletter', [NewsletterController::class, 'traiteFormulaire']);

// Jour 5 : exercice
Route::get('voiture', [VoitureController::class, 'rendFormulaire']);
Route::post('voiture', [VoitureController::class, 'traiteFormulaire']);