<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Adopter;

class AdopterSeeder extends Seeder
{
    public function run(): void
    {
        $adopters = [
            ['name' => 'João Silva', 'email' => 'joao@email.com', 'password' => bcrypt('catscats123'), 'phone' => '912345678'], 
            ['name' => 'Maria Santos', 'email' => 'maria@email.com', 'password' => bcrypt('ilovecats123'), 'phone' => '934567890'], 
            ['name' => 'Ricardo Costa', 'email' => 'ricardo@email.com', 'password' => bcrypt('ilovedogs123'), 'phone' => '911223344'] 
        ];

        foreach ($adopters as $adopter) {
            Adopter::create($adopter);
        }
    }
}