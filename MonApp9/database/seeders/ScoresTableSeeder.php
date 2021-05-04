<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScoresTableSeeder extends Seeder
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
    public function run()
    {
        DB::table('scores')->delete();
        for ($i = 1; $i <= 50; $i++) {
            $date = $this->randDate();
            DB::table('scores')->insert([
                'nbSecondes' => rand(1, 200),
                'pourcentageBonnesReponses' => rand(1, 100),
                'user_id' => rand(1, 10),
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }
    }
}
