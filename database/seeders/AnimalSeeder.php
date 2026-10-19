<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Animal;

class AnimalSeeder extends Seeder
{
    public function run(): void
    {
        $animals = [
            ['species' => 'dog', 'breed' => 'Labrador Retriever', 'age' => 3, 'size' => 'large', 'status' => 'available'], 
            ['species' => 'dog', 'breed' => 'Beagle', 'age' => 2, 'size' => 'medium', 'status' => 'available'], 
            ['species' => 'dog', 'breed' => 'German Shepherd', 'age' => 5, 'size' => 'large', 'status' => 'unavailable'], 
            ['species' => 'dog', 'breed' => 'Poodle', 'age' => 1, 'size' => 'small', 'status' => 'adopted'], 
            ['species' => 'cat', 'breed' => 'Siamese', 'age' => 1, 'size' => 'small', 'status' => 'available'], 
            ['species' => 'cat', 'breed' => 'Persian', 'age' => 4, 'size' => 'small', 'status' => 'unavailable'], 
            ['species' => 'cat', 'breed' => 'Maine Coon', 'age' => 3, 'size' => 'large', 'status' => 'available'], 
            ['species' => 'cat', 'breed' => 'British Shorthair', 'age' => 2, 'size' => 'medium', 'status' => 'adopted'], 
        ];

        foreach ($animals as $animal) {
            Animal::create($animal);
        }
    }
}