<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository
{
    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function create(array $data)
    {
        return $this->contact->create($data);
    }

    public function getAllWithPagination($perPage = 10)
    {
        return $this->contact->orderBy('name', 'asc')->paginate($perPage);
    }

    public function getAllWithFilters($filters = [], $perPage = 10)
    {
        $query = Contact::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['cpf'])) {
            $query->where('cpf', $filters['cpf']);
        }

        return $query->orderBy('name', 'asc')->paginate($perPage);
    }
}
