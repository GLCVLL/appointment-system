<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ClosedDay;
use App\Models\OpeningHour;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Calendar view.
     */
    public function index(Request $request)
    {
        // Get filters
        $filter_services = $request->filter_services ?? [];

        // Get all services and users
        $services = Service::all();
        $users = User::all();

        // Create time array
        $start = Carbon::createFromTimeString('00:00');
        $end = Carbon::createFromTimeString('23:30');
        $interval = 'PT30M'; // Period Time di 30 minuti
        $period = new CarbonPeriod($start, $interval, $end);
        $time_array = [];

        foreach ($period as $date) {
            $time_array[] = [
                'value' => $date->format('H:i'),
                'text' => $date->format('H:i'),
            ];
        }

        // Get Opening Hours
        $openingHours = OpeningHour::all();

        // Get closed days
        $closedDays = ClosedDay::pluck('date')->toArray();

        // Get all appointments
        $appointments = Appointment::with(['user', 'services'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();


        // Create events
        $events = [];

        foreach ($appointments as $appointment) {

            $title = "{$appointment->user->name}";

            foreach ($appointment->services as $service) {
                $title .= " - {$service->name}";
            }

            $events[] = [
                'title' => $title,
                'start' => "{$appointment->date}T{$appointment->start_time}",
                'end' => "{$appointment->date}T{$appointment->end_time}",
                'data' => $appointment,
            ];
        }

        return view('admin.calendar.index', compact('events', 'users', 'services', 'filter_services', 'time_array', 'openingHours', 'closedDays'));
    }
}
