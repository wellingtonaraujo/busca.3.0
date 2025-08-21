<?php

namespace Database\Seeders;

use App\Models\Adm\Sexo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //limpar a tabela
        $cleared = Sexo::truncate();

        // pupular a tabela
        $created = Sexo::create(['descricao'=>'masculino']);
        $created = Sexo::create(['descricao'=>'feminino']);
    }
}
