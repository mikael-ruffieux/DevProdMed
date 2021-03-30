<?php

namespace App\Http\Controllers;

use DateInterval;
use Illuminate\Http\Request;
use DateTime;

class FormController extends Controller
{
    public function afficheForm() {
        $etudiants = $this->recupereEtudiants();

        return view('view_form')->with('etudiants', $etudiants);
    }

    // L'objet $request contiendra les données du formulaire ;-)
    public function traiteForm(Request $request) { 

        $heureDebut = strtotime($request->input('heureDebut'));
        $heureFin = strtotime($request->input('heureFin'));
        $tempsPause = $request->input('tempsPause');

        //dd($tempsPause, $heureFin-$heureDebut);

        $tempsParEtudiant = ($heureFin-$heureDebut)/sizeof($request->input('etudiants')); // Intervale en sec/etudiant, avec la pause

        $horaires = [];
        $etudiants = [];

        while($heureDebut <= $heureFin) {
            $horaires[] = date("H:i", $heureDebut);
            $heureDebut += $tempsParEtudiant;
        }

        $i = 0;
        foreach ($request->input('etudiants') as $nomEtudiant) {
            $etudiants[] = ['nom'=> $nomEtudiant, 'debut'=> $horaires[$i], 'fin'=> $horaires[$i+1]];
            $i += 1;
        }
    
        //dd($horaires, $etudiants, $tempsParEtudiant);

        //Remarque : request devient requete (pour une compréhension du méchanisme)
        return view('view_resultat')->with(["etudiants" => $etudiants]);
    }

    private function recupereEtudiants() {
        $path = storage_path('app/textes/etudiants.txt');
        $etudiants = file($path);

        return $etudiants;

    }
}
