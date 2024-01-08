<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Service;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Generator $faker): void
    {
        $services = Service::pluck('id')->toArray();

        for ($i = 0; $i <= 2; $i++) {
            $appointment = new Appointment();
            $appointment->user_id = 1;
            $appointment->date = $faker->dateTimeBetween('+1 days', '+3 days');
            $appointment->start_time = $faker->time("H:i:s");
            $appointment->end_time = $faker->time("H:i:s");
            $appointment->save();

            $selectedServices = [];

            foreach ($services as $service) {
                if (rand(0, 1)) $selectedServices[] = $service;
            }

            if (!count($selectedServices)) Arr::random($services);

            $appointment->services()->attach($selectedServices);
        };
    }
}
