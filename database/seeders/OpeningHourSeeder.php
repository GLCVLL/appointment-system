<?php

namespace Database\Seeders;

use App\Models\OpeningHour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpeningHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $opening_hours_data = config('data.opening_hours');

        foreach ($opening_hours_data as $data) {

            $opening_hour = new OpeningHour();

            $opening_hour->day = $data['day'];
            $opening_hour->opening_time = $data['opening_time'];
            $opening_hour->closing_time = $data['closing_time'];
            $opening_hour->break_start = $data['break_start'];
            $opening_hour->break_end = $data['break_end'];

            $opening_hour->save();
        }
    }
}
