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
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'dob' => '1999-06-28',
            // 'uni_registration_no' => '12345',
            'type' => 'admin',
            'is_hod' => 0,
            'address' => 'address',
            'father_name' => 'U Tester',
            'nrc' => '12/PABATA(N)456456',
            'gender' => 'male',
            'phone' => '1234567890',
        ]);
        \App\Models\User::create([
            'name' => 'Test student',
            'email' => 'student@example.com',
            'username' => 'student',
            'password' => bcrypt('password'),
            'dob' => '1999-06-28',
            // 'uni_registration_no' => '12345',
            'type' => 'student',
            'is_hod' => 0,
            'address' => 'address',
            'father_name' => 'U Tester',
            'nrc' => '12/PABATA(N)53457',
            'gender' => 'male',
            'phone' => '64566',
        ]);
        \App\Models\User::create([
            'name' => 'Test teacher',
            'email' => 'teacher@example.com',
            'username' => 'teacher',
            'password' => bcrypt('password'),
            'dob' => '1999-06-28',
            // 'uni_registration_no' => '12345',
            'type' => 'teacher',
            'is_hod' => 0,
            'address' => 'address',
            'father_name' => 'U Tester',
            'nrc' => '12/PABATA(N)5678678',
            'gender' => 'female',
            'phone' => '87885555',
        ]);
    }
}
