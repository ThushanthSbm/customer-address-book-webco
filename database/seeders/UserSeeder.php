<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing records from the users table
        DB::table('users')->truncate(); // Deletes all existing rows and resets the auto-increment ID

        // Insert new user data
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@softui.com',
                'password' => Hash::make('secret'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'thushanth',
                'email' => 'thushanth@thushanth.com',
                'password' => Hash::make('thushanth'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'adminthushanth',
                'email' => 'adminthushanth@admin.com',
                'password' => Hash::make('adminthushanth'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
