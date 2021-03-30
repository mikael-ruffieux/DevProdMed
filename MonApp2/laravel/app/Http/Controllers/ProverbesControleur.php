<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gestionnaires\GestionnaireProverbesHardcode;
use App\Gestionnaires\GestionnaireProverbesTexte;
use App\Gestionnaires\GestionnaireProverbesWikipedia;

class ProverbesControleur extends Controller
{
    public function affiche10Proverbes() {

        //$gestionnaire = new GestionnaireProverbesHardcode();
        //$gestionnaire = new GestionnaireProverbesTexte();
        $gestionnaire = new GestionnaireProverbesWikipedia();
        $proverbes = $gestionnaire->rendProverbes();

        shuffle($proverbes);

        $les10proverbes = array();

        for ($i=0; $i < 10; $i++) {
            array_push($les10proverbes, $proverbes[$i]);
        }

        return view('view_proverbes')->with(['source' => $gestionnaire->rendSource(),'proverbes' => $les10proverbes]);
    }
}
