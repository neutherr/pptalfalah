<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL', 'admin@alfalah.com');
        $password = env('ADMIN_PASSWORD', 'alfalah2026');
        $name     = env('ADMIN_NAME', 'Admin');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make($password),
            ]
        );
    }
}
