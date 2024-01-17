<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Creating an admin user
        $admin = new User();

        $admin->name = 'Admin';
        $admin->email = 'admin@mail.it';
        $admin->password = bcrypt('password');
        $admin->role = 'admin';

        $admin->save();

        // Creating a normal user
        $user = new User();
        $user->name = 'User';
        $user->email = 'user@mail.it';
        $user->password = bcrypt('password');
        $user->role = 'user';
        $user->save();
    }
}
