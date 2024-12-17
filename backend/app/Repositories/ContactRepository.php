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
}
