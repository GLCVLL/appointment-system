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
        if (!isset($filters['date_min']) || !isset($filters['date_max']) || $filters['date_max'] < $filters['date_min']) {
            $filters['date_min'] = Carbon::now()->subWeek()->format('Y-m-d');
            $filters['date_max'] = Carbon::now()->format('Y-m-d');
        }


        //*** STATS ***//
        // Get selected period stats
        $stats = $this->calcStats($filters['date_min'], $filters['date_max']);

        // Previous Date Period
        $date_min = Carbon::createFromFormat('Y-m-d', $filters['date_min']);
        $date_max = Carbon::createFromFormat('Y-m-d', $filters['date_max']);
        $prev_period_start = $date_min->copy()->subDays($date_max->diffInDays($date_min));
        $prev_period_end = $date_min->copy()->subDay();

        // Get previous period stats
        $stats_prev = $this->calcStats($prev_period_start, $prev_period_end);

        // Create stats vars
        $clients_chart = $stats['clients_chart'];
        $clients_chart_prev = $stats_prev['clients_chart'];
        $appointments_chart = $stats['appointments_chart'];
        $appointments_chart_prev = $stats_prev['appointments_chart'];
        $profits_chart = $stats['profits_chart'];
        $profits_chart_prev = $stats_prev['profits_chart'];

        // Calculate increments
        $clients_chart['increment'] = $this->calcIncrement($clients_chart['tot'], $clients_chart_prev['tot']);
        $appointments_chart['increment'] = $this->calcIncrement($appointments_chart['tot'], $appointments_chart_prev['tot']);
        $profits_chart['increment'] = $this->calcIncrement($profits_chart['tot'], $profits_chart_prev['tot']);

        return view('admin.home', compact('filters', 'clients_chart', 'appointments_chart', 'profits_chart'));
    }


    /**
     * Calculate stats based on a date period
     */
    public function calcStats($date_min, $date_max)
    {

        //*** DATA ***//
        $clients_chart = [
            'data' => [],
            'labels' => [],
            'tot' => 0,
            'increment' => 0,
        ];
        $appointments_chart = [
            'data' => [],
            'labels' => [],
            'tot' => 0,
            'increment' => 0,
        ];
        $profits_chart = [
            'data' => [],
            'labels' => [],
            'tot' => 0,
            'increment' => 0,
        ];
        $period = new CarbonPeriod($date_min, $date_max);

        // Get resources
        $clients = User::where('role', 'user')
            ->whereDate('created_at', '>=', $date_min)
            ->whereDate('created_at', '<=', $date_max)
            ->orderBy('created_at')
            ->get();

        $appointments = Appointment::with('services')
            ->whereDate('date', '>=', $date_min)
            ->whereDate('date', '<=', $date_max)
            ->get();


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


        return [
            'clients_chart' => $clients_chart,
            'appointments_chart' => $appointments_chart,
            'profits_chart' => $profits_chart,
        ];
    }


    /**
     * Calculate stat increment in percentage
     */
    public function calcIncrement($sum, $prev_sum)
    {
        // Check division by 0
        $divisor = $prev_sum === 0 ? 1 : $prev_sum;

        // Calculate increment
        $increment = ($sum - $prev_sum) / $divisor * 100;

        return number_format($increment, 0);
    }
}
