<?php

namespace Database\Seeders;

use App\Models\Adm\UserStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //limpando status
        $result = UserStatus::truncate();
        // semeando status
        $status = [
            ['descricao' =>'ATIVO'],
            ['descricao' =>'INATIVO'],
        ];

        foreach ($status as $item) {
            $created = UserStatus::create($item);
        }

    }
}
