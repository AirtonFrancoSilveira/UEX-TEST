<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permitir que todos os usuários autenticados possam acessar
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'cpf' => 'required|cpf|unique:contacts,cpf',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'cep' => 'required|string|size:8',
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
