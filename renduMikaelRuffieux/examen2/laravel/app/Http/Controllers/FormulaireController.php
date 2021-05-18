<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormulaireRequest;

class FormulaireController extends Controller
{
    public function retourneFormulaire() {
        return view("formulaire");
    }

    public function traiteFormulaire(FormulaireRequest $request) {
        return view("resultat")->with('results', $request->all());
    }
}
