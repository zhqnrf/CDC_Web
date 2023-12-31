<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            QuisProdiSeeder::class,
            UserSeeder::class,
            FollowerSeeder::class,
            EducationSeeder::class,
            ProvinceSeeder::class,
            RegencySeeder::class,
            AdminSeeder::class,
            PostSeeder::class,
            NewsSeeder::class,
            JobsSeeder::class,
            ProdiAdministratorSeeder::class,
            WhatsappsSeeder::class
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}