<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function store(ContactRequest $request)
    {
        try {
            $contact = $this->contactService->createContact($request->validated());

            return response()->json([
                'message' => 'Contato cadastrado com sucesso!',
                'data' => $contact
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['name', 'cpf']);
        $perPage = $request->get('per_page', 10);
        $contacts = $this->contactService->getContacts($filters, $perPage);

        return response()->json([
            'message' => 'Contatos listados com sucesso!',
            'data' => $contacts
        ]);
    }

}
