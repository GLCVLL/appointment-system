<?php

namespace Database\Seeders;

use App\Models\ClosedDay;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClosedDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $closed_day = new ClosedDay();
        $closed_day->date = Carbon::parse('2024-12-24');
        $closed_day->save();
    }
}
