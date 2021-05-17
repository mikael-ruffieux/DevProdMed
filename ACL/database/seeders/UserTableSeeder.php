<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        
        DB::table('users')->insert([
            'name' => 'joe',
            'email' => 'joe@email.ch',
            'password' => Hash::make('mdp_joe'),
        ]); // avec le role par défaut ;-)
        
        DB::table('users')->insert([
            'name' => 'lea',
            'email' => 'lea@email.ch',
            'password' => Hash::make('mdp_lea'),
            'role' => User::ROLE_EDITEUR,
        ]);
        
        DB::table('users')->insert([
            'name' => 'dom',
            'email' => 'dom@email.ch',
            'password' => Hash::make('mdp_dom'),
            'role' => User::ROLE_ADMIN,
        ]);
    }
}
