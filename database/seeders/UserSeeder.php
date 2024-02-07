<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Generator $faker): void
    {

        // Creating an admin user
        $admin = new User();

        $admin->name = 'Admin';
        $admin->email = 'admin@mail.it';
        $admin->password = bcrypt('password');
        $admin->role = 'admin';

        $admin->save();


        // Creating clients
        for ($i = 0; $i < 5; $i++) {

            $user = new User();
            $user->role = 'user';

            $user->name = $faker->name();
            $user->email = "user$i@mail.it";
            $user->password = bcrypt('password');
            $user->save();
        }
    }
}
