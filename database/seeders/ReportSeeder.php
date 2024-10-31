<?php

namespace Database\Seeders;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = [
            [
                'report_type_id' => 1,
                'user_id' => 2,
                'post_id' => 16,
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'report_type_id' => 2,
                'user_id' => 2,
                'post_id' => 28,
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'report_type_id' => 3,
                'user_id' => 2,
                'post_id' => 25,
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Report::insert($reports);
    }
}
