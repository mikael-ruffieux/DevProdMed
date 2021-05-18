<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonneProjetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('personne_projet')->delete();

        /* 
    
        Jacques [1] travaille sur les projets :

        - Projet parc éolien [1]
        - Projet gaz [2]

        */
        
        DB::table('personne_projet')->insert([
            'personne_id' => 1,
            'projet_id' => 1
        ]);

        DB::table('personne_projet')->insert([
            'personne_id' => 1,
            'projet_id' => 2
        ]);

        /*

        Orianne [2] travaille sur les projets :

        - Projet gaz [2]
        - Projet `petroleum` [3]

        */

        DB::table('personne_projet')->insert([
            'personne_id' => 2,
            'projet_id' => 2
        ]);

        DB::table('personne_projet')->insert([
            'personne_id' => 2,
            'projet_id' => 3
        ]);

        /*

        Dominique [3] travaille sur le projet :

        - Projet parc éolien [1]

        */

        DB::table('personne_projet')->insert([
            'personne_id' => 3,
            'projet_id' => 1
        ]);


    }
}
