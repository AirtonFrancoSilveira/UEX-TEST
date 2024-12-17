<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Validação de CPF customizada
        Validator::extend('cpf', function ($attribute, $value, $parameters, $validator) {
            // Remove caracteres não numéricos
            $cpf = preg_replace('/[^0-9]/', '', $value);

            if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }

            // Validação do dígito verificador
            for ($t = 9; $t < 11; $t++) {
                $d = 0;
                for ($c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            return true;
        }, 'O :attribute não é um CPF válido.');
    }
}
