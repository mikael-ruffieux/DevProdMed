<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoitureRequest extends FormRequest
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
            'marque'=>'required|min:3|max:20|alpha_dash',
            'type'=>'required|min:3|max:20|alpha_dash',
            'couleur'=>'required|min:3|max:20|alpha_dash',
            'cylindree'=>'required',
        ];
    }
}
