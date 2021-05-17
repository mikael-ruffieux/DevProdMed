<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CarsTableSeeder extends Seeder
{
    private function randDate() {
        $nbJours = rand(-2800, 0);
        return Carbon::now()->addDays($nbJours);
    }

    private $car_brands = [
        'Peugeot', 'Ferrari', 'Renault', 'Aston Martin', 'Toyota', 'Telsa'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('cars')->delete();
        for ($i = 1; $i <= 100; $i++) {
            $date = $this->randDate();
            DB::table('cars')->insert([
                'brand' => $this->car_brands[rand(0, 5)],
                'model' => 'Modèle'. $i,
                'user_id' => rand(1, 10),
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }
    }
}
