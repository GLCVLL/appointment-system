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
        // Date Filter
        $filters = $request->all();

        // Validate
        if (!isset($filters['date_min']) || !isset($filters['date_max']) || $filters['date_max'] <= $filters['date_min']) {
            $filters['date_min'] = Carbon::now()->subWeek()->format('Y-m-d');
            $filters['date_max'] = Carbon::now()->format('Y-m-d');
        }

        // Create Date Period
        $period = new CarbonPeriod($filters['date_min'], $filters['date_max']);


        // Create Clients stats
        $clients_data = [];
        $clients_labels = [];

        $clients = User::where('role', 'user')
            ->whereDate('created_at', '>=', $filters['date_min'])
            ->whereDate('created_at', '<=', $filters['date_max'])
            ->orderBy('created_at')
            ->get();

        foreach ($period as $day) {

            // Filter clients by date
            $filtered_clients = $clients->filter(function ($client) use ($day) {
                return Carbon::parse($client->created_at)->format('Y-m-d') === $day->format('Y-m-d');
            });

            // Create chart data
            $clients_data[] = count($filtered_clients);
            $clients_labels[] = $day->format('m/d');
        }


        // Create Profits stats
        $profits_data = [];
        $profits_labels = [];

        $appointments = Appointment::with('services')
            ->whereDate('date', '>=', $filters['date_min'])
            ->whereDate('date', '<=', $filters['date_max'])
            ->get();

        foreach ($period as $day) {

            // Filter appointments by date
            $filtered_appointments = $appointments->filter(function ($appointment) use ($day) {
                return $appointment->date === $day->format('Y-m-d');
            });

            // Create chart data
            $profits_data[] = $filtered_appointments->sum(function ($appointment) {

                // Sum all services price for this appointment
                $services_sum = 0;
                foreach ($appointment->services as $service) {
                    $services_sum += $service->price;
                }

                return $services_sum;
            });

            $profits_labels[] = $day->format('m/d');
        }


        return view('admin.home', compact('filters', 'clients_labels', 'clients_data', 'profits_labels', 'profits_data'));
    }
}
