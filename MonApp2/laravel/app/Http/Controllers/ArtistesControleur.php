<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DOMDocument;

class ArtistesControleur extends Controller
{
    public function afficheArtistes($l) {
        $artistes = array(
            array("nom" => "Amy", "prenom" => "Winehouse", "dateNaissance" => new DateTime('14-09-1983')),
            array("nom" => "Janis", "prenom" => "Joplin", "dateNaissance" => new DateTime('19-01-1943')),
            array("nom" => "Jo", "prenom" => "Bar", "dateNaissance" => new DateTime('19-01-1943')),
            array("nom" => "Janis", "prenom" => "Siegel", "dateNaissance" => new DateTime('12-01-1990')),
        );

        $artistesFiltres = array();

        foreach ($artistes as $artiste) {
            if(strtolower($artiste["nom"][0]) == strtolower($l)) {
                array_push($artistesFiltres, $artiste);
            }
        }

        return view('view_artistes')->with('artistes', $artistesFiltres);
    }
}
