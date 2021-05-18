<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonnesTableSeeder extends Seeder
{
    protected $noms = ['jacques', 'orianne', 'dominique'];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('personnes')->delete();
        for ($i = 1; $i <= 3; $i++) {
            DB::table('personnes')->insert([
                'id' => $i,
                'nom'=> $this->noms[$i-1],
                'email'=> $this->noms[$i-1]."@boite$i.ch",
                'motdepasse'=>Hash::make('mdp' . $i)
            ]);
        }
    }
}
