<?php 

namespace App\Gestionnaires;

/**
 * Tous les gestionnaires de proverbes doivent :
 *  - Rendre des proverbes
 *  - Indiquer d'où proviennent ces proverbes
 */

 interface IGestionnaireProverbes {
     /**
      * Doit rentre un tableau de proverbes
      */
      public function rendProverbes();

      /**
       * Permet de savoir d'où proviennent les proverbes
       * @return string "Source : Proverbes provenant d'un fichier texte"
       */
      public function rendSource();
 }