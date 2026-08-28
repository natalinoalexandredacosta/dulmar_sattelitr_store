<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'natalinoalexandredacosta@gmail.com',
            ],
            [
                'name' => 'Dulmar Administrator',
                'password' => Hash::make('Dulmar@12345'),
                'email_verified_at' => now(),
            ]
        );
    }
}