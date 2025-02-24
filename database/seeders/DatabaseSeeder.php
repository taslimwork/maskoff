<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call(UserRoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(SiteSettingSeeder::class);
        $this->call(CMSPageSeeder::class);
        $this->call(FAQSeeder::class);
        $this->call(EventTypeSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(strategySeeder::class);
        $this->call(PostStatusSeeder::class);
        $this->call([
            GroupTypeSeeder::class,
            GroupSeeder::class,
            GroupMembersSeeder::class,
            ContactUsSeeder::class,
            ReportTypeSeeder::class,
            ReportSeeder::class,
        ]);


        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
