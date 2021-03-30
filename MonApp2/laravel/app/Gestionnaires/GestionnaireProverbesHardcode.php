<?php

namespace App\Gestionnaires;

class GestionnaireProverbesHardcode implements IGestionnaireProverbes {

    public function rendProverbes() {
        $proverbes = array(
            "à grands seigneurs, peu de paroles",
            "à la chandeleur l’hiver cesse ou reprend vigueur",
            "à la chandelle, la chèvre semble demoiselle",
            "à la guerre comme à la guerre, à la guerre tous les coups sont permis",
            "à la meilleure femme le meilleur vin",
            "à laver la tête d’un âne, on perd sa lessive",
            "à l’heureux l’heureux",
            "à l’impossible nul n’est tenu",
            "à l’ongle on connaît le lion",
            "à l’œuvre on connaît l’artisan",
        );
        return $proverbes;
    }

    public function rendSource() {
          return "Source : Proverbes 'hardcodés'";
      }
}