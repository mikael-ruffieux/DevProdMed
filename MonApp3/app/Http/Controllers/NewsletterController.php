<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Models\Email;

class NewsletterController extends Controller
{
    public function rendFormulaire() {
        return view('view_newsletter_formulaire');
    }

    public function traiteFormulaire(NewsletterRequest $request) {
        // sauvegarde de l'email dans la base de données
        $unModeleEmail = new Email();
        $unModeleEmail->email = $request->input('email');
        $unModeleEmail->save();
        
        return view('view_newsletter_confirm_inscription');
    }
}
