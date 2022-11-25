<?php

namespace Database\Seeders;

use App\Models\User;
use DateTime;
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
                'nama' => 'Admin',
                'nip' => null,
                'pangkat_golongan' => null,
                'jabatan' => null,
                'username' => 'admin',
                'email' => 'admin@email.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'is_admin' => true,
            ],
            [
                'nama' => 'User',
                'nip' => null,
                'pangkat_golongan' => null,
                'jabatan' => null,
                'username' => 'user',
                'email' => 'user@email.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'is_admin' => false,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
