<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory; // Import the FakerFactory

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create(); // Create an instance of Faker

        // Generate and insert random FAQ data
        for ($i = 1; $i <= 10; $i++) { // Insert 10 random FAQ entries
            Faq::create([
                'question' => $faker->sentence,
                'answer' => $faker->paragraph,
                'active'=>1,
            ]);
        }
    }
}
