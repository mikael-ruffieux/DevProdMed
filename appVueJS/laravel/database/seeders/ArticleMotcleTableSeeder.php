<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ArticleMotcleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('article_motcle')->delete();
        for ($i = 1; $i <= 100; $i++) {
            $numbers = range(1, 20);
            shuffle($numbers);
            $n = rand(3, 6);
            for ($j = 1; $j <= $n; $j++) {
                DB::table('article_motcle')->insert([
                    'article_id' => $i,
                    'motcle_id' => $numbers[$j]]
                );
            }
        }
    }
}
