<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        Staff::create([
            'name' => 'Administrador',
            'email' => 'admin@email.com',
            'password' => bcrypt('dogandcat123')
        ]);

        Staff::create([
            'name' => 'Funcionario 2',
            'email' => 'staff2@email.com',
            'password' => bcrypt('lovesanimals123')
        ]);
    }
}
