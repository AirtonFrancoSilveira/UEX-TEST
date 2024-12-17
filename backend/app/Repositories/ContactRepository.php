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
}
