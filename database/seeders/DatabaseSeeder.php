<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     // 1. Tạo tài khoản QUẢN TRỊ VIÊN (Admin)
    //     User::create([
    //         'name' => 'Hệ thống Admin',
    //         'email' => 'admin@gmail.com',
    //         'password' => Hash::make('12345678'),
    //         'role' => 'admin',
    //     ]);

    //     // 2. Tạo tài khoản GIẢNG VIÊN (Instructor)
    //     User::create([
    //         'name' => 'Giảng viên AI',
    //         'email' => 'instructor@gmail.com',
    //         'password' => Hash::make('12345678'),
    //         'role' => 'instructor',
    //     ]);

    //     // 3. Tạo tài khoản SINH VIÊN (Student)
    //     User::create([
    //         'name' => 'Học viên Ưu tú',
    //         'email' => 'student@gmail.com',
    //         'password' => Hash::make('12345678'),
    //         'role' => 'student',
    //     ]);
    // }
}