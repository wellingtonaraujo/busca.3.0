<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //limpar a tabela
        $truncated = User::truncate();

        $create = [
            'profile_id'     => 1,
            'user_status_id' => 1,
            'name'           => 'wellington de araujo ferreira',
            'cpf'            => '32517394253',
            'nascimento'     => '1969-05-04',
            'cep'            => '68903740',
            'logradouro'     => 'avenida walter jucá',
            'numero'         => '365',
            'complemento'    => '',
            'bairro'         => 'Zerão',
            'cidade'         => 'macapá',
            'uf'             => 'ap',
            'email'          => 'wellington.araujoferreira@gmail.com',
            'celular'        => '96981037477',
            'password'       => 'wellington',
        ];

        $created = User::create($create);
    }
}
