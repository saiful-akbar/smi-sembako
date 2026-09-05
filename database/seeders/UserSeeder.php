<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Owner',
                'email' => 'owner@mail.com',
                'password' => Hash::make('owner123'),
                'role' => 'owner',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('admin123'),
                'role' => 'manager',
            ],
            [
                'name' => 'Kasir',
                'email' => 'kasir@mail.com',
                'password' => Hash::make('kasir23'),
                'role' => 'cashier',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
