<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chama as seeders específicas
        $this->call([
            AclSeeder::class,
            ProfileSeeder::class,
            MenuSeeder::class,
            RouteMetodoSeeder::class,
            RouteSeeder::class,
            EntidadeSeeder::class,
            // ProfileRouteSeeder::class,
            PessoaSeeder::class,
        ]);
    }
}

