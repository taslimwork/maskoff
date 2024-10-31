<?php

namespace Database\Seeders;

use App\Models\GroupMembers;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'group_id' => '1',
                'user_id' => '2',
                'is_admin' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],[
                'group_id' => '2',
                'user_id' => '2',
                'is_admin' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        GroupMembers::insert($data);
    }
}
