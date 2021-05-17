<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MotclesTableSeeder extends Seeder
{
    private function randDate() {
        return Carbon::createFromDate(null, rand(1, 12), rand(1, 28));
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('motcles')->delete();
        for ($i = 1; $i <= 20; $i++) {
            $date = $this->randDate();
            DB::table('motcles')->insert([
                'mot' => 'mot' . $i,
                'mot_url' => 'mot' . $i,
                'created_at' => $date,
                'updated_at' => $date]
            );
        }
    }
}
