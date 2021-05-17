<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AclTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acls')->delete();

        DB::table('acls')->insert(['role' =>
        User::ROLE_BASIC, 'fonctionnalite' => 'UnControleur@index']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_BASIC, 'fonctionnalite' => 'UnControleur@show']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_EDITEUR, 'fonctionnalite' => 'UnControleur@index']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_EDITEUR, 'fonctionnalite' => 'UnControleur@show']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_EDITEUR, 'fonctionnalite' => 'UnControleur@create']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_EDITEUR, 'fonctionnalite' => 'UnControleur@edit']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_EDITEUR, 'fonctionnalite' => 'UnControleur@store']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_ADMIN, 'fonctionnalite' => 'UnControleur@index']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_ADMIN, 'fonctionnalite' => 'UnControleur@show']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_ADMIN, 'fonctionnalite' => 'UnControleur@create']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_ADMIN, 'fonctionnalite' => 'UnControleur@edit']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_ADMIN, 'fonctionnalite' => 'UnControleur@store']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_ADMIN, 'fonctionnalite' => 'UnControleur@update']);
        DB::table('acls')->insert(['role' =>
        User::ROLE_ADMIN, 'fonctionnalite' => 'UnControleur@destroy']);
    }
}
