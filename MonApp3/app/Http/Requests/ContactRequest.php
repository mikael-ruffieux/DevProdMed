<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // par défaut, l'utilisateur est authorisé à envoyer une requête
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nom'=>'required|min:3|max:20|alpha_dash',
        	'email'=>'required|email',
        	'texte'=>'required|max:250'
        ];
    }
}
