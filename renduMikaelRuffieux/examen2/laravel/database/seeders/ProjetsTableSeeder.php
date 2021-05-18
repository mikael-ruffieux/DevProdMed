<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjetsTableSeeder extends Seeder
{
    protected $noms = ['parc éolien', 'gaz', 'petroleum'];
    protected $descriptions = [
        'Six éoliennes (23 GWh / an)',
        'Exploration du sous-sol',
        'Exploration des fonds du Léman'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projets')->delete();
        for ($i = 1; $i <= 3; $i++) {
            DB::table('projets')->insert([
                'id' => $i,
                'nom'=> 'Projet ' . $this->noms[$i-1],
                'description'=> $this->descriptions[$i-1],
                'termine' => rand(0,1),
            ]);
        }
    }
}
