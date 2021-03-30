<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function rendFormulaire () {
        return view("view_formulaire_contact");
    }

    public function valideEtTraiteFormulaire(ContactRequest $request) {

        // Envoi d'un mail
        Mail::send('view_contenu_email', $request->all(), function($message){
		    $message->to('mikael.ruffieux@heig-vd.ch')->subject('Laravel (Contact)');
        });
        
        // Confirmation de réception
        return view('view_confirmation');
    }
}
