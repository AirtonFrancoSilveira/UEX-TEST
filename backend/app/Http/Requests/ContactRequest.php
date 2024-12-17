<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'cpf' => 'required|cpf|unique:contacts,cpf',
            'phone' => ['required', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'], // Formato (11) 91234-5678 ou (11) 1234-5678
            'cep' => ['required', 'regex:/^\d{5}-\d{3}$/'], // Formato 12345-678
            'address' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'cpf.cpf' => 'O CPF informado é inválido.',
            'cep.size' => 'O CEP deve conter exatamente 8 dígitos.',
        ];
    }
}
