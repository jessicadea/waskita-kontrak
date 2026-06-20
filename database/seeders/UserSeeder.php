<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'phone' => '081111111111',
                'role' => 'admin',
            ],
            [
                'name' => 'Client',
                'email' => 'client@gmail.com',
                'phone' => '082222222222',
                'role' => 'client',
            ],
            [
                'name' => 'Pegawai',
                'email' => 'pegawai@gmail.com',
                'phone' => '083333333333',
                'role' => 'pegawai',
            ],
            [
                'name' => 'Pimpinan',
                'email' => 'pimpinan@gmail.com',
                'phone' => '084444444444',
                'role' => 'pimpinan',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'phone' => $user['phone'],
                    'role' => $user['role'],
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}