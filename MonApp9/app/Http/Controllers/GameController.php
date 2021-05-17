<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{

    public function index() {
        Session::put("key", "value");
        $scores = Score::orderBy('nbSecondes','ASC')->take(3)->get();
        return view('index')->with('scores', $scores);
    }

    private function createQuestions() {
        $questions = [];
        $id = 1;
        for ($i=2; $i <= 12; $i++) { 
            // La clé est la question, la valeur est la réponse.
            $val1 = rand(1, 12);
            do {
                $val2 = rand(1, 12);
            } while ($val1 == $val2);

            $questions["q$id"] = ['val1' => $i, 'val2' => $val1];
            $id++;
            $questions["q$id"] = ['val1' => $i, 'val2' => $val2];
            $id++;
        }
        // Stockage dans la Session
        Session::put('questions', $questions);

        return $questions;
    }

    public function play() {
        $this->createQuestions();
        Session::put('startTime', time());
        return view('play')->with('questions', Session::get("questions"));
    }

    public function checkResults(Request $request) {

        $userAnswers = $request->answers;

        $correctAnswers = Session::get("questions");

        $userPoints = 0;

        foreach ($userAnswers as $id => $answer) {
            if($answer == $correctAnswers[$id]['val1'] * $correctAnswers[$id]['val2']) {
                $userPoints ++;
            }
            $correctAnswers[$id]["userAnswer"] = $answer;
        }

        $data = [
            "results" => $correctAnswers,
            "time" => time()-Session::get('startTime'),
            "score" => round($userPoints / sizeof($correctAnswers) * 100, 2)
        ];

        // Si l'utilisateur est authentifié
        if($request->user()) {
            // transmet les 3 meilleurs scores de l'utilisateur
            $user = $request->user();
            // et enregistre celui-ci
            Score::create([
                "nbSecondes" => $data["time"],
                "pourcentageBonnesReponses" => $data["score"],
                "user_id" => $user->id
            ]);
        } else {
            $user = null;
        } 

        return view('view_results')->with('data', $data)->with('user', $user);
    }
}
