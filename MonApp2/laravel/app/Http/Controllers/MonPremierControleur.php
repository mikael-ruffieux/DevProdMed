<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonPremierControleur extends Controller
{
    public function maMethodeDansControleur() {
        return "YES!";

        //return view('welcome');
    }

    public function test($n, $c) {
        return $n . " : " . $c;
    }

    public function afficheImage() {
        return view('monImage');
    }
}
