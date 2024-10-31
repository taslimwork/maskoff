<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) { // Adjust the loop to generate the desired number of events
            DB::table('events')->insert([
                'name' => $faker->sentence,
                'slug' => $faker->slug,
                'event_type_id' => $faker->numberBetween(1, 13), // Assuming event_type_id is between 1 and 10
                'user_id' => $faker->numberBetween(2,3), // Assuming event_type_id is between 1 and 10
                // 'image' => $faker->imageUrl(),
                'organizer_name' => $faker->name,
                'event_date' => $faker->date(),
                'event_time' => $faker->time('H:i'),
                'event_location' => $faker->address,
                'description' => $faker->paragraph,
                'event_features' => $faker->paragraph,
                'sponsor_information' => json_encode([
                    ['sponsor_name' => $faker->company],
                    ['sponsor_name' => $faker->company],
                    ['sponsor_name' => $faker->company],
                    ['sponsor_name' => $faker->company]
                ]),
            ]);
        }
    }
}
