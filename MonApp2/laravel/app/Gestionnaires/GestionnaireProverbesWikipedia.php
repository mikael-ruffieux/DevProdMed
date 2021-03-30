<?php

namespace App\Gestionnaires;

use DOMDocument;

class GestionnaireProverbesWikipedia implements IGestionnaireProverbes {

    private function recupereTags($url) {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile($url);
        libxml_use_internal_errors(false);

        $tagsLi = $doc->getElementsByTagName("li");

        

        $tagsTexte = [];

        for ($i=0; $i < $tagsLi->length; $i++) { 
            $tagLi = $tagsLi->item($i);
            $tagsTexte[] = $tagLi->nodeValue;
          
        }

        return $tagsTexte;

    }

    private function nettoieTags($tagsTexte) {

        $proverbes = [];
        $trouveDeclancheurEnregistrement = false;
        $pos=-1;
        $fin = false;
        // parcours de tous les tags <li> pour ne garder que les bons
        do {
            $pos++;
            $tagTexte = $tagsTexte[$pos];
            if (!$trouveDeclancheurEnregistrement) {
                if (preg_match("/24 Liens externes/", $tagTexte)) {
                    $trouveDeclancheurEnregistrement = true;
                }
            } else {
                // on s'arrête lorsqu'on a trouvé le mot Wiktionnaire
                if (preg_match("/Wiktionnaire/", $tagTexte)) {
                    $fin = true;
                } else {
                    $proverbes[] = $tagTexte;
                }
            }
        } while (!$fin && ($pos < count($tagsTexte)));

        dd($proverbes);

        return $proverbes;
    }

    public function rendProverbes() {
        $url = 'https://fr.wiktionary.org/wiki/Annexe:Liste_de_proverbes_fran%C3%A7ais';
        $tagsTexte = $this->recupereTags($url);
        $proverbes = $this->nettoieTags($tagsTexte);


        return $proverbes;
    }

    public function rendSource() {
          return "Source : Proverbes provenant de Wikipedia";
      }
}