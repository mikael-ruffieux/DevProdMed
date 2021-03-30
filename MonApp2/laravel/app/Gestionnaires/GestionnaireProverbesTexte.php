<?php

namespace App\Gestionnaires;

class GestionnaireProverbesTexte implements IGestionnaireProverbes {

    public function rendProverbes() {
        $path = storage_path('app/textes/proverbes.txt');
        $proverbes = file($path);
        return $proverbes;
    }

    public function rendSource() {
          return "Source : Proverbes provenant d'un fichier texte";
      }
}