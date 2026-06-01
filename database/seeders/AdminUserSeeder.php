<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            [
                'email' => 'admin@ajmalnoorwisata.com',
            ],
            [
                'name' => 'Admin Ajmal Noor Wisata',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}