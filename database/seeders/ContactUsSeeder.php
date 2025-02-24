<?php

namespace Database\Seeders;

use App\Models\ContactUs;
use Database\Factories\ContactUsFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactUs::factory(10)->create();
    }
}
