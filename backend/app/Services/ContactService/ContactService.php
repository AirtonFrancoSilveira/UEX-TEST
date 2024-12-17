<?php

namespace App\Services\ContactService;

use App\Repositories\ContactRepository;
use Illuminate\Support\Facades\Http;
use App\Models\Contact;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function createContact($data)
    {
        if (Contact::where('cpf', $data['cpf'])->exists()) {
            throw new \Exception('O CPF informado já está cadastrado.');
        }

        $verifySSL = env('APP_ENV') === 'local' ? false : true;

        $cep = $data['cep'];
        $response = Http::withOptions(['verify' => $verifySSL])->get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->failed()) {
            throw new \Exception('Erro ao consultar o CEP.');
        }

        $cepData = $response->json();

        $data['address'] = $cepData['logradouro'] ?? 'Endereço não encontrado';

        return $this->contactRepository->create($data);
    }

    public function getContacts($perPage = 10)
    {
        return $this->contactRepository->getAllWithPagination($perPage);
    }
}
