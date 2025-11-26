<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\OpeningHour;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
        $services = Service::select('id', 'duration')->get();
        $usersIds = User::pluck('id')->toArray();

        for ($i = 0; $i <= 100; $i++) {

            // Choose a random services
            $selectedServices = [];
            $appointment_duration = 0;
            foreach ($services as $service) {
                if (rand(0, 3) > 2) {
                    $selectedServices[] = $service->id;
                    $appointment_duration += Carbon::createFromTimeString($service->duration)->secondsSinceMidnight() / 60;
                }
            }

            // Add a random service if empty
            if (!count($selectedServices)) {
                $rand_service = $services->random();
                $selectedServices[] =  $rand_service->id;
                $appointment_duration += Carbon::createFromTimeString($rand_service->duration)->secondsSinceMidnight() / 60;
            }


            // Get a random date
            $is_available = false;
            $date = null;
            $start_time = '';
            $end_time = '';
            $retry = 0;
            while (!$is_available && $retry < 300) {

                // Get a date
                $date = Carbon::parse($faker->dateTimeBetween('-30 days', '+30 days'));

                // Check if is a working day
                $openingHour = OpeningHour::where('day', $date->englishDayOfWeek)->first();

                // Get available slot
                if ($openingHour) {

                    // Get formatted date
                    $app_date = $date->format('Y-m-d');

                    // Create slots period
                    $app_slot_start = Carbon::createFromTimeString($openingHour->opening_time);
                    $app_slot_end = Carbon::createFromTimeString($openingHour->closing_time)->subMinutes($appointment_duration);
                    $app_slots = iterator_to_array(new CarbonPeriod($app_slot_start, 'PT30M', $app_slot_end));

                    $slot_retry = 0;
                    while (count($app_slots) > 0 && !$is_available && $slot_retry < 50) {

                        // Choose a random slot
                        $rand_slot = Arr::random($app_slots);
                        $start_time = $rand_slot->copy()->format('H:i:s');
                        $end_time = $rand_slot->copy()->addMinutes($appointment_duration)->format('H:i:s');

                        //  Get overlapping appointments
                        $appointments_count = Appointment::where('date', $app_date)
                            ->where('missed', false)
                            ->where('start_time', '<', $end_time)
                            ->where('end_time', '>', $start_time)
                            ->count();

                        // Check if overlapping or out of working hours
                        if (
                            !$appointments_count &&
                            $start_time >= $app_slot_start->format('H:i:s') &&
                            $end_time <= $app_slot_end->format('H:i:s')
                        ) {
                            $is_available = true;
                        }
                        $slot_retry++;
                    }
                }

                $retry++;
            }

            // Create appointment if available (retry exceded)
            if ($is_available) {
                $appointment = new Appointment();
                $appointment->user_id = Arr::random($usersIds);
                $appointment->date = $app_date;
                $appointment->start_time =  $start_time;
                $appointment->end_time = $end_time;
                $appointment->save();

                $appointment->services()->attach($selectedServices);
            }
        };
    }
}
