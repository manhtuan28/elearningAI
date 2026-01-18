<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $name = $row['ho_va_ten'] ?? null;
        $email = $row['email_duy_nhat'] ?? $row['email'] ?? null;

        if (empty($name) || empty($email)) {
            return null;
        }

        if (User::where('email', $email)->exists()) {
            return null; 
        }

        $roleMap = [
            'Sinh viên'     => 'student',
            'Giảng viên'    => 'instructor',
            'Quản trị viên' => 'admin',
        ];
        
        $roleInput = $row['vai_tro_chon_tu_danh_sach'] ?? $row['vai_tro'] ?? 'Sinh viên';
        $systemRole = $roleMap[$roleInput] ?? 'student';

        $rawPassword = $row['mat_khau_bo_trong_12345678'] ?? $row['mat_khau'] ?? null;
        $password = ($rawPassword && trim($rawPassword) !== '') ? $rawPassword : '12345678';

        return new User([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
            'role'     => $systemRole,
        ]);
    }
}