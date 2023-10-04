<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(OrganizationSeeder::class);

        User::factory()->create([
            'name' => 'mario',
            'email' => 'mario@gmail.com',
            'organization_id' => 1
        ]);

        User::factory()->create([
            'name' => 'Teste',
            'email' => 'teste@gmail.com',
            'organization_id' => 1

        ]);

        $this->call(CountrySeeder::class);


    }
}
