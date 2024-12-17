<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function store(ContactRequest $request)
    {
        // Validação dos dados e criação do contato
        $contact = $this->contactService->createContact($request->validated());

        // Resposta JSON forçada
        return response()->json([
            'message' => 'Contato cadastrado com sucesso!',
            'data' => $contact
        ], 201);
    }
}
