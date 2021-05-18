<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormulaireController;

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
    return redirect('/quizz');
});

Route::get('quizz', [FormulaireController::class, 'retourneFormulaire']);

Route::post('traiteQuizz', [FormulaireController::class, 'traiteFormulaire']);
