<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidarCampos implements Rule
{
    protected $tipo;

    public function __construct($tipo)
    {
        $this->tipo = $tipo;
    }

    public function passes($attribute, $value)
    {
        switch ($this->tipo) {
            case 'cpf':
                return $this->validarCpf($value);
            case 'cnpj':
                return $this->validarCnpj($value);
            case 'celular':
                return $this->validarCelular($value);
            case 'celular_ddd':
                return $this->validarCelularComDdd($value);
            case 'cep':
                return $this->validarCep($value);
            case 'email':
                return $this->validarEmail($value);
            case 'data':
                return $this->validarData($value);
            default:
                return false;
        }
    }

    public function message()
    {
        switch ($this->tipo) {
            case 'cpf':
                return 'O CPF informado é inválido.';
            case 'cnpj':
                return 'O CNPJ informado é inválido.';
            case 'celular':
                return 'O número de celular informado é inválido.';
            case 'celular_ddd':
                return 'O número de celular com DDD informado é inválido.';
            case 'cep':
                return 'O CEP informado é inválido.';
            case 'email':
                return 'O e-mail informado é inválido.';
            case 'data':
                return 'A data informada é inválida.';
            default:
                return 'O campo informado é inválido para o tipo ' . $this->tipo . '.';
        }
    }

    private function validarCpf($value)
    {
        $cpf = preg_replace('/[^0-9]/', '', $value);
        if (strlen($cpf) != 11 || preg_match('/^(\d)\1*$/', $cpf)) {
            return false;
        }
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
    }

    private function validarCnpj($value)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $value);
        if (strlen($cnpj) != 14 || preg_match('/^(\d)\1*$/', $cnpj)) {
            return false;
        }
        $tamanho = strlen($cnpj) - 2;
        $numeros = substr($cnpj, 0, $tamanho);
        $digitos = substr($cnpj, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;
        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros[$tamanho - $i] * $pos--;
            if ($pos < 2) $pos = 9;
        }
        $resultado = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);
        if ($resultado != $digitos[0]) {
            return false;
        }
        $tamanho++;
        $numeros = substr($cnpj, 0, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;
        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros[$tamanho - $i] * $pos--;
            if ($pos < 2) $pos = 9;
        }
        $resultado = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);
        return $resultado == $digitos[1];
    }

    private function validarCelular($value)
    {
        // Celular sem DDD: 9 dígitos, começando por 9
        return preg_match('/^9\d{8}$/', preg_replace('/[^0-9]/', '', $value));
    }

    private function validarCelularComDdd($value)
    {
        // Celular com DDD: 2 dígitos DDD + 9 dígitos, começando por 9
        return preg_match('/^\d{2}9\d{8}$/', preg_replace('/[^0-9]/', '', $value));
    }

    private function validarCep($value)
    {
        // CEP: 5 dígitos + hífen + 3 dígitos ou apenas 8 dígitos
        return preg_match('/^\d{5}-?\d{3}$/', $value);
    }

    private function validarEmail($value)
    {
        // Regex simples para email
        return preg_match('/^[\w\.-]+@[\w\.-]+\.\w{2,}$/', $value);
    }

    private function validarData($value)
    {
        // Aceita formatos: d/m/Y, d-m-Y, Y-m-d, Y/m/d
        $formatos = [
            'd/m/Y',
            'd-m-Y',
            'Y-m-d',
            'Y/m/d',
        ];
        foreach ($formatos as $formato) {
            $data = \DateTime::createFromFormat($formato, $value);
            if ($data && $data->format($formato) === $value) {
                // Verifica se a data realmente existe
                $y = (int)$data->format('Y');
                $m = (int)$data->format('m');
                $d = (int)$data->format('d');
                if (checkdate($m, $d, $y)) {
                    return true;
                }
            }
        }
        // Tenta converter datas já em formato timestamp ou aceitas pelo strtotime
        $data = date_create($value);
        if ($data) {
            $y = (int)$data->format('Y');
            $m = (int)$data->format('m');
            $d = (int)$data->format('d');
            if (checkdate($m, $d, $y)) {
                return true;
            }
        }
        return false;
    }
}
