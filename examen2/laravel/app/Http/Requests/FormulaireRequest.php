<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormulaireRequest extends FormRequest
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
            'prenom' => 'required|min:2',
            'age' => 'required', // |regex:[1-9]|[1-9][0-9]|1[0-1][0-9]|12[0-8]
            'q1' => 'required',
            'genre' => 'required',
            'preferences' => 'required',
        ];
    }
}
