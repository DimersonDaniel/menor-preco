<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Importacao extends FormRequest
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
            'fileUpload'  =>  ['required','file', 'max:30024', $this->checkExtensao()],
        ];
    }

    public function messages()
    {
        return [

            'fileUpload.required' =>  'Arquivo .xlsx é obrigatório.',
            'fileUpload.file'     =>  'Arquivo .xlsx é obrigatório.',
            'fileUpload.max'      =>  'Arquivo superior a 30MB',
        ];
    }

    private function checkExtensao()
    {
        return function ($attribute, $value, $fail)
        {
            $allow = ['xlsx'];

            $extensao = $this->file($attribute)->getClientOriginalExtension();

            if(!in_array($extensao ,$allow))
            {
                $fail('Extensão inválida.');
            }
        };

    }

}
