<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = (string) config('admin.default_email', 'admin@example.com');
        $adminPassword = (string) config('admin.default_password', '');
        if ($adminPassword === '') {
            $adminPassword = Str::random(20);
        }

        \App\Models\User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin',
                'password' => Hash::make($adminPassword),
            ]
        );
    }
}
