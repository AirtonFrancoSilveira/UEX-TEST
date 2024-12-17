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

        $addressEncoded = urlencode($data['address']);
        $googleResponse = Http::withOptions(['verify' => false])->get("https://maps.googleapis.com/maps/api/geocode/json", [
            'address' => $addressEncoded,
            'key' => env('GOOGLE_MAPS_API_KEY'),
        ]);

        if ($googleResponse->failed() || empty($googleResponse['results'])) {
            throw new \Exception('Não foi possível obter as coordenadas geográficas.');
        }

        $location = $googleResponse['results'][0]['geometry']['location'];
        $data['latitude'] = $location['lat'];
        $data['longitude'] = $location['lng'];

        return $this->contactRepository->create($data);
    }

    public function getContacts($filters = [], $perPage = 10)
    {
        return $this->contactRepository->getAllWithFilters($filters, $perPage);
    }

    public function updateContact($id, $data)
    {
        if (!empty($data['cpf']) && Contact::where('cpf', $data['cpf'])->where('id', '!=', $id)->exists()) {
            throw new \Exception('O CPF informado já está cadastrado em outro contato.');
        }

        return $this->contactRepository->update($id, $data);
    }

    public function deleteContact($id)
    {
        return $this->contactRepository->delete($id);
    }


}
