<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\OpeningHour;
use App\Models\Service;
use Carbon\Carbon;
use Faker\Generator;
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

        for ($i = 0; $i <= 10; $i++) {

            // Get random date
            $openingHour = null;
            $date = null;
            while (!$openingHour) {
                $date = Carbon::parse($faker->dateTimeBetween('+1 days', '+7 days'));
                $day_of_week = $date->englishDayOfWeek;
                $openingHour = OpeningHour::where('day', $day_of_week)->first();
            }

            // Create appointment
            $appointment = new Appointment();
            $appointment->user_id = 1;
            $appointment->date = $date;
            $appointment->start_time =  Carbon::parse($openingHour->opening_time)->addHour(rand(0, 4));
            $appointment->end_time = Carbon::parse($appointment->start_time)->addHour();
            $appointment->save();

            // Add at least 1 service
            $selectedServices = [];

            foreach ($services as $service) {
                if (rand(0, 1)) $selectedServices[] = $service;
            }

            if (!count($selectedServices)) $selectedServices[] =  Arr::random($services);

            $appointment->services()->attach($selectedServices);
        };
    }
}
