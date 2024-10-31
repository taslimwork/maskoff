<?php

namespace Database\Seeders;

use App\Models\GroupType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Truncate the table
            DB::statement('TRUNCATE TABLE group_types');

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $data = [
            [
                'type' => 'Primary Group',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'type' => 'Task Group',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'type' => 'Erratic Group',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'type' => 'Formal Group',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'type' => 'Strategic Group',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'type' => 'Friendship Group',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'type' => 'Psychological Group',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        GroupType::insert($data);
    }
}
