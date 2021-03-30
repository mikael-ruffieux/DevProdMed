<?php

namespace App\Http\Controllers;

use App\Rules\ManifRule;
use App\Http\Requests\ManifestationRequest;
use Illuminate\Support\Facades\Mail;

class ManifestationController extends Controller
{
    public function rendFormulaire () {
        return view("view_formulaire_manifestation");
    }

    public function valideEtTraiteFormulaire(ManifestationRequest $request) {

        $request->validate(['fin' => new ManifRule($request->debut)]);

        // Envoi d'un mail
        Mail::send('view_email_manifestation', $request->all(), function($message){
		    $message->to('mikael.ruffieux@heig-vd.ch')->subject('Laravel (Contact)');
        });
        
        // Confirmation de réception 
        return view('view_confirmation_manifestation');
    }
}
