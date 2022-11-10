<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@email.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'User',
                'username' => 'user',
                'email' => 'user@email.com',
                'password' => bcrypt('password'),
                'role' => 'petugas',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
