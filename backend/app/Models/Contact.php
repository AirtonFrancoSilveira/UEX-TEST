<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory; // Adicione este trait

    protected $fillable = [
        'name', 'cpf', 'phone', 'cep', 'address', 'latitude', 'longitude',
    ];
}