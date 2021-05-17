<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArticlesTableSeeder extends Seeder
{
    private function randDate() {
        $nbJours = rand(-2800, 0);
        return Carbon::now()->addDays($nbJours);
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('articles')->delete();
        for ($i = 1; $i <= 100; $i++) {
            $date = $this->randDate();
            DB::table('articles')->insert([
                'titre' => 'Titre' . $i,
                'contenu' => 'Contenu ' . $i . ' Lorem ipsum dolor sit amet, consectetur ' .
                'adipiscing elit. Proin vel auctor libero, quis venenatis ' .
                'augue. Curabitur a pulvinar tortor, vitae condimentum ' .
                'libero. Cras eu massa sed lorem mattis lacinia. ' .
                'Vestibulum id feugiat turpis. Proin a lorem ligula.',
                'user_id' => rand(1, 10),
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }
    }
}
