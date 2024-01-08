<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Generator $faker): void
    {
        for ($i = 0; $i <= 2; $i++) {
            $appointment = new Appointment();
            $appointment->user_id = 1;
            $appointment->date = $faker->dateTimeBetween('+1 days', '+3 days');
            $appointment->start_time = $faker->time("H:i:s");
            $appointment->end_time = $faker->time("H:i:s");
            $appointment->save();
        };
    }
}
