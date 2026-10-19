<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $applications = [
            ['animal_id' => 1, 'adopter_id' => 1, 'status' => 'approved', 'rejection_reason' => null], 
            ['animal_id' => 2, 'adopter_id' => 2, 'status' => 'submitted', 'rejection_reason' => null], 
            ['animal_id' => 3, 'adopter_id' => 3, 'status' => 'under-review', 'rejection_reason' => null], 
            ['animal_id' => 4, 'adopter_id' => 1, 'status' => 'rejected', 'rejection_reason' => 'Nao possui espaco suficiente.'], 
            ['animal_id' => 5, 'adopter_id' => 2, 'status' => 'approved', 'rejection_reason' => null], 
            ['animal_id' => 6, 'adopter_id' => 3, 'status' => 'submitted', 'rejection_reason' => null], 
            ['animal_id' => 7, 'adopter_id' => 1, 'status' => 'rejected', 'rejection_reason' => 'Condicoes inadequadas para um gato de grande porte.'], 
            ['animal_id' => 8, 'adopter_id' => 2, 'status' => 'approved', 'rejection_reason' => null] 
        ];

        foreach ($applications as $app) {
            Application::create($app);
        }
    }
}