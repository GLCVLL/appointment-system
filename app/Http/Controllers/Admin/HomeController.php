<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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


        // Get Clients
        $clients = User::where('role', 'user')
            ->where('created_at', '>=', $filters['date_min'])
            ->where('created_at', '<=', $filters['date_max'])
            ->orderBy('created_at')
            ->get();


        // Create Clients stats
        $clients_data = [];
        $clients_labels = [];
        foreach ($period as $day) {

            // Filter clients by date
            $filtered_clients = $clients->filter(function ($client) use ($day) {
                return Carbon::parse($client->created_at)->format('Y-m-d') === $day->format('Y-m-d');
            });

            // Create chart data
            $clients_data[] = count($filtered_clients);
            $clients_labels[] = $day->format('m-d');
        }


        return view('admin.home', compact('filters', 'clients_labels', 'clients_data'));
    }
}
