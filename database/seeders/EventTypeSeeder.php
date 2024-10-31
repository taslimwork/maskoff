<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            ['name'=>'Personal Event'],
            [ 'name'=>"Conferences"],
            [ 'name'=>"Trade Shows"],
            [ 'name'=>"Seminars"],
            [ 'name'=>"Corporate Off-Sites & Executive Meetings"],
            [ 'name'=>"Company Parties"],
            [ 'name'=>"Product Launches"],
            [ 'name'=>"Networking"],
            [ 'name'=>"Festivals"],
            [ 'name'=>"Food Truck Festivals"],
            [ 'name'=>"Virtual Training Sessions"],
            [ 'name'=>"Team Building Activities"],
            [ 'name'=>"Virtual Recruiting Events"]
        ];



        foreach ($events as $value)
        {
            EventType::create($value);
        }
    }
}
