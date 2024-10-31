<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Talk Is Cheap',
                'type_id' => '6',
                'created_by' => '2',
                'moto' => "Together we're stronger.",
                'description' => 'A teamwork slogan is a phrase that helps motivate employees in a workplace. Managers can post slogans on papers or signs around the workplace, type them at the ends of emails or occasionally say them during team meetings. Outside the workplace, leaders use them to propel sports teams, liven fundraisers or motivate any group of people into taking action.',
                'status' => 1
            ],
            [
                'name' => 'Tech Turtles',
                'type_id' => '1',
                'created_by' => '2',
                'moto' => "'Many minds spring many ideas.",
                'description' => 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour,',
                'status' => 1
            ]
        ];

        Group::insert($groups);
    }
}
