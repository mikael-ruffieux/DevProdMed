<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;

class GameController extends Controller
{
    public function index() {
        $scores = Score::orderBy('nbSecondes','ASC')->take(3)->get();
        //  dd($scores); 
        return view('index')->with('scores', $scores);
    }

    private function createQuestions() {
        $questions = [];
        for ($i=2; $i <= 12; $i++) { 
            // La clé est la question, la valeur est la réponse.
            $val1 = rand(1, 12);
            do {
                $val2 = rand(1, 12);
            } while ($val1 == $val2);

            array_push($questions, 
                [
                    'question' => "$i * $val1",
                    'answer' => $i * $val1
                ], 
                [
                    'question' => "$i * $val2",
                    'answer' => $i * $val2
                ]);
        }
        return $questions;
    }

    public function play() {
        $questions = $this->createQuestions();
        return view('play')->with('questions', $questions);
    }

    public function checkResults(Request $request) {

        $data = [
            'answers' => $request->all(),
            'time' => 10,
            'score' => 49,
        ];

        // Si l'utilisateur est authentifié
        if($request->user()) {
            // transmet les 3 meilleurs scores de l'utilisateur
            $user = $request->user();
            // et enregistre celui-ci

        } else {
            $user = null;
        } 

        return view('view_results')->with('data', $data)->with('user', $user);
    }
}
