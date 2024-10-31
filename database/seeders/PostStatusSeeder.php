<?php

namespace Database\Seeders;

use App\Models\PostStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class PostStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Faker::create();

        $userIds = User::role('USER')->pluck('id');

        foreach (range(1, 10) as $index) {
            $data = PostStatus::create([
                'parent_id' => 0, // Assuming some posts might be children
                'post_type' => $faker->randomElement(['text', 'photo', 'video', 'audio', 'poll', 'article']),//'text', 'photo', 'video', 'audio', 'poll', 'article'
                'user_id' => $faker->randomElement($userIds), // Assuming you have at least 10 users
                'post_description' => $faker->realText(100),
                'isSpam' => $faker->boolean(1), // 10% chance to be spam
                'active' => $faker->boolean(95), // 90% chance to be active
                'post_audience'=>$faker->randomElement(['public', 'friends', 'only_me'])
            ]);

            if(in_array($data->post_type, ['photo', 'video', 'audio', 'poll', 'article']))//'photo', 'video', 'audio', 'poll', 'article'
            {
                foreach (range(1, 2) as $index) {
                    DB::table('post_status_details')->insert([
                        'post_status_id' =>$data->id,
                        'post_contents' => ($data->post_type == 'article') ? $faker->url :  $faker->paragraphs(rand(1, 3), true),
                    ]);
                }
            }
            foreach (range(1, 2) as $index) {
                PostStatus::create([
                    'parent_id' => $data->id, // Assuming some posts might be children
                    'post_type' => 'text',
                    'user_id' => $faker->randomElement($userIds), // Assuming you have at least 10 users
                    'post_description' => $faker->realText(100),
                    'isSpam' => 0, // 10% chance to be spam
                    'active' => 1 ,// 90% chance to be active
                    'post_audience'=>$faker->randomElement(['public'])
                ]);
            }

        }
    }
}
