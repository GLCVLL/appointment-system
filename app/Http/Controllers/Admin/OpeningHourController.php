<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OpeningHour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OpeningHourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $openingHours = OpeningHour::all();

        return view('admin.opening-hours.index', compact('openingHours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        // Create empty resource
        $openingHour = new OpeningHour();

        // Create time array
        $start = Carbon::parse('00:00');
        $end = Carbon::parse('23:30');
        $step = 30;
        $time_array = [];

        while ($start <= $end) {
            $time_array[] = [
                'value' => $start->format('H:i:s'),
                'text' => $start->format('H:i'),
            ];
            $start->addMinutes($step);
        }


        return view('admin.opening-hours.create', compact('openingHour', 'time_array'));
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
                'day.required' => __('opening_hours.validation.day_required'),
                'day.string' => __('opening_hours.validation.day_string'),
                'day.unique' => __('opening_hours.validation.day_unique'),

                'opening_time.required' => __('opening_hours.validation.opening_time_required'),
                'opening_time.date_format' => __('opening_hours.validation.opening_time_format'),

                'closing_time.required' => __('opening_hours.validation.closing_time_required'),
                'closing_time.date_format' => __('opening_hours.validation.closing_time_format'),

                'break_start.date_format' => __('opening_hours.validation.break_start_format'),

                'break_end.date_format' => __('opening_hours.validation.break_end_format'),
            ]
        );


        // Insert Opening Hours
        $opening_hour = new OpeningHour();
        $opening_hour->fill($data);
        $opening_hour->save();


        return to_route('admin.opening-hours.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('opening_hours.created'),
                    'timestamp' => now()
                ]
            ]);
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
        // Create time array
        $start = Carbon::parse('00:00');
        $end = Carbon::parse('23:30');
        $step = 30;
        $time_array = [];

        while ($start <= $end) {
            $time_array[] = [
                'value' => $start->format('H:i:s'),
                'text' => $start->format('H:i'),
            ];
            $start->addMinutes($step);
        }

        return view('admin.opening-hours.edit', compact('openingHour', 'time_array'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OpeningHour $openingHour)
    {
        // Validation
        $data = $request->validate(
            [
                'day' => ['required', 'string', Rule::unique('opening_hours')->ignore($openingHour->id)],
                'opening_time' => 'required|date_format:H:i:s',
                'closing_time' => 'required|date_format:H:i:s',
                'break_start' => 'nullable|date_format:H:i:s',
                'break_end' => 'nullable|date_format:H:i:s',
            ],
            [
                'day.required' => __('opening_hours.validation.day_required'),
                'day.string' => __('opening_hours.validation.day_string'),
                'day.unique' => __('opening_hours.validation.day_unique'),

                'opening_time.required' => __('opening_hours.validation.opening_time_required'),
                'opening_time.date_format' => __('opening_hours.validation.opening_time_format'),

                'closing_time.required' => __('opening_hours.validation.closing_time_required'),
                'closing_time.date_format' => __('opening_hours.validation.closing_time_format'),

                'break_start.date_format' => __('opening_hours.validation.break_start_format'),

                'break_end.date_format' => __('opening_hours.validation.break_end_format'),
            ]
        );


        // Update Opening Hour
        $openingHour->update($data);


        return to_route('admin.opening-hours.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('opening_hours.updated'),
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OpeningHour $openingHour)
    {
        // Delete Opening Hour
        $openingHour->delete();

        return to_route('admin.opening-hours.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('opening_hours.deleted'),
                    'timestamp' => now()
                ]
            ]);
    }
}
