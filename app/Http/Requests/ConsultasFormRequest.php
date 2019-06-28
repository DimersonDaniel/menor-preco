<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultasFormRequest extends FormRequest
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
            'search'  =>  'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'search.required'       =>  'Codigo de barras é obrigatório.',
            'search.numeric'        =>  'Informe um codigo de barras valido.',
        ];
    }

}
