<?php

namespace App\Services\ContactService;

use App\Repositories\ContactRepository;
use Illuminate\Support\Facades\Http;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function createContact($data)
    {
        // Desabilita verificação SSL apenas para ambiente local
        $verifySSL = env('APP_ENV') === 'local' ? false : true;

        // Consulta ao ViaCEP
        $cep = $data['cep'];
        $response = Http::withOptions(['verify' => $verifySSL])->get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->failed()) {
            throw new \Exception('Erro ao consultar o CEP.');
        }

        $cepData = $response->json();

        // Continuação da lógica de criação do contato
        $data['address'] = $cepData['logradouro'] ?? 'Endereço não encontrado';

        return $this->contactRepository->create($data);
    }
}
