<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'titre'=>'required|max:80',
            'contenu'=>'required',
            // ajout de l'expression régulière de validation des mots-clés
            'motcles' => ['Regex:/^[A-Za-z0-9-àéèêëïôùû]{1,50}?(,[A-Za-z0-9-àéèêëïôùû]{1,50})*$/']
        ];
    }
}
