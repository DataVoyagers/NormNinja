<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin', 
            'email' => 'admin@normninja.com', 
            'password' => Hash::make('password'), 
            'role' => 'admin', 
            'is_active' => true
        ]);

        // Create Teacher
        User::create([
            'name' => 'Test Teacher',
            'email' => 'teacher@normninja.com',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
            'is_active' => true
        ]);

        // Create Student
        User::create([
            'name' => 'Test Student',
            'email' => 'student@normninja.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'student_id' => 'STU001',
            'is_active' => true
        ]);

        echo "✅ Users created successfully!\n";
    }
}