<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        //*** FILTERS ***//
        $filters = $request->all();

        // Validate
        if (!isset($filters['date_min']) || !isset($filters['date_max']) || $filters['date_max'] <= $filters['date_min']) {
            $filters['date_min'] = Carbon::now()->subWeek()->format('Y-m-d');
            $filters['date_max'] = Carbon::now()->format('Y-m-d');
        }

        //*** DATA ***//
        // Create Date Period
        $period = new CarbonPeriod($filters['date_min'], $filters['date_max']);

        // Get all data
        $clients = User::where('role', 'user')
            ->whereDate('created_at', '>=', $filters['date_min'])
            ->whereDate('created_at', '<=', $filters['date_max'])
            ->orderBy('created_at')
            ->get();

        $appointments = Appointment::with('services')
            ->whereDate('date', '>=', $filters['date_min'])
            ->whereDate('date', '<=', $filters['date_max'])
            ->get();



        //*** STATS ***//
        $clients_chart = [];
        $appointments_chart = [];
        $profits_chart = [];

        foreach ($period as $day) {

            // Retrieve data until today date
            if ($day->format('Y-m-d') <= Carbon::now()->format('Y-m-d')) {

                // CLIENTS
                // Filter clients by date
                $filtered_clients = $clients->filter(function ($client) use ($day) {
                    return Carbon::parse($client->created_at)->format('Y-m-d') === $day->format('Y-m-d');
                });
                $clients_chart['data'][] = count($filtered_clients);


                // APPOINTMENTS
                // Filter appointments by date
                $filtered_appointments = $appointments->filter(function ($appointment) use ($day) {

                    $appointment_date_end = Carbon::parse($appointment->date . 'T' . $appointment->end_time);

                    return $appointment->date === $day->format('Y-m-d') && $appointment_date_end < Carbon::now();
                });
                $appointments_chart['data'][] = count($filtered_appointments);


                // PROFITS
                // Filter appointments by date
                $filtered_appointments = $appointments->filter(function ($appointment) use ($day) {

                    $appointment_date_end = Carbon::parse($appointment->date . 'T' . $appointment->end_time);

                    return $appointment->date === $day->format('Y-m-d') && $appointment_date_end < Carbon::now();
                });

                // Create chart data
                $profits_chart['data'][] = $filtered_appointments->sum(function ($appointment) {

                    // Sum all services price for this appointment
                    $services_sum = 0;
                    foreach ($appointment->services as $service) {
                        $services_sum += $service->price;
                    }

                    return $services_sum;
                });
            }

            // Retrieve all days labels
            $clients_chart['labels'][] = $day->format('d M');
            $appointments_chart['labels'][] = $day->format('d M');
            $profits_chart['labels'][] = $day->format('d M');
        }

        // Get total stats
        $clients_chart['tot'] = array_sum($clients_chart['data']);
        $appointments_chart['tot'] = array_sum($appointments_chart['data']);
        $profits_chart['tot'] = array_sum($profits_chart['data']);


        return view('admin.home', compact('filters', 'clients_chart', 'appointments_chart', 'profits_chart'));
    }
}
