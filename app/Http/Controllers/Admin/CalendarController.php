<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
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

        // Get all services
        $services = Service::all();

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
            ];
        }

        return view('admin.calendar.index', compact('events', 'services', 'filter_services'));
    }
}
