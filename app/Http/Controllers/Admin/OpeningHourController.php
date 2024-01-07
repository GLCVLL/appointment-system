<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OpeningHour;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OpeningHourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $opening_hours = OpeningHour::all();

        return view('admin.opening-hours.index', compact('opening_hours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        // Create empty resource
        $opening_hour = new OpeningHour();

        // Create time array
        $start = Carbon::parse('00:00');
        $end = Carbon::parse('23:30');
        $step = 30;
        $time_array = [];

        while ($start <= $end) {
            $time_array[] = $start->format('H:i');
            $start->addMinutes($step);
        }


        return view('admin.opening-hours.create', compact('opening_hour', 'time_array'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $data = $request->validate(
            [
                'day' => 'required|string|unique:opening_hours',
                'opening_time' => 'required|date_format:H:i',
                'closing_time' => 'required|date_format:H:i',
                'break_start' => 'nullable|date_format:H:i',
                'break_end' => 'nullable|date_format:H:i',
            ],
            [
                'day.required' => 'The day is required',
                'day.string' => 'The day must be a string',
                'day.unique' => 'This day already exists',

                'opening_time.required' => 'The opening Time is required',
                'opening_time.date_format' => 'Insert a valide time',

                'closing_time.required' => 'The closing Time is required',
                'closing_time.date_format' => 'Insert a valide time',

                'break_start.date_format' => 'Insert a valide time',

                'break_end.date_format' => 'Insert a valide time',
            ]
        );


        // Insert Opening Hours
        $opening_hour = new OpeningHour();
        $opening_hour->fill($data);
        $opening_hour->save();


        return to_route('admin.opening-hours.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(OpeningHour $openingHour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OpeningHour $openingHour)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OpeningHour $openingHour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OpeningHour $openingHour)
    {
        //
    }
}
