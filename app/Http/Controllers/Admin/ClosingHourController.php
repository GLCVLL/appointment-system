<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClosingHour;
use App\Models\OpeningHour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClosingHourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $closingHours = ClosingHour::orderBy('date', 'desc')->paginate(10);

        return view('admin.closing-hours.index', compact('closingHours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Create empty resource
        $closingHour = new ClosingHour();

        // Get available times based on a default day (Monday)
        $timeArray = $this->getAvailableTimes('Monday');

        return view('admin.closing-hours.create', compact('closingHour', 'timeArray'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $data = $request->validate(
            [
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ],
            [
                'date.required' => __('closing_hours.validation.date_required'),
                'date.date' => __('closing_hours.validation.date_format'),
                'date.after_or_equal' => __('closing_hours.validation.date_future'),
                'start_time.required' => __('closing_hours.validation.start_time_required'),
                'start_time.date_format' => __('closing_hours.validation.start_time_format'),
                'end_time.required' => __('closing_hours.validation.end_time_required'),
                'end_time.date_format' => __('closing_hours.validation.end_time_format'),
                'end_time.after' => __('closing_hours.validation.end_time_after'),
            ]
        );

        // Validate that the date has opening hours
        $dayOfWeek = Carbon::parse($data['date'])->englishDayOfWeek;
        $openingHour = OpeningHour::where('day', $dayOfWeek)->first();

        if (!$openingHour) {
            return back()->withInput($request->input())
                ->withErrors(['date' => __('closing_hours.validation.no_opening_hours')]);
        }

        // Validate that times are within opening hours
        $startTime = Carbon::parse($data['start_time'])->format('H:i:s');
        $endTime = Carbon::parse($data['end_time'])->format('H:i:s');

        if (
            $startTime < $openingHour->opening_time
            || $startTime >= $openingHour->closing_time
            || $endTime > $openingHour->closing_time
            || $endTime <= $openingHour->opening_time
        ) {
            return back()->withInput($request->input())
                ->withErrors(['start_time' => __('closing_hours.validation.outside_opening_hours')]);
        }

        // Check if a closing hour for this date already exists
        $existingClosingHour = ClosingHour::where('date', $data['date'])->first();

        if ($existingClosingHour) {
            return back()->withInput($request->input())
                ->withErrors(['date' => __('closing_hours.validation.date_unique')]);
        }

        // Insert Closing Hour
        $closingHour = new ClosingHour();
        $closingHour->fill($data);
        $closingHour->save();

        return to_route('admin.closing-hours.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('closing_hours.created'),
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClosingHour $closingHour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClosingHour $closingHour)
    {
        // Get day of week from the closing hour date
        $dayOfWeek = Carbon::parse($closingHour->date)->englishDayOfWeek;
        $timeArray = $this->getAvailableTimes($dayOfWeek);

        return view('admin.closing-hours.edit', compact('closingHour', 'timeArray'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClosingHour $closingHour)
    {
        // Validation
        $data = $request->validate(
            [
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ],
            [
                'date.required' => __('closing_hours.validation.date_required'),
                'date.date' => __('closing_hours.validation.date_format'),
                'date.after_or_equal' => __('closing_hours.validation.date_future'),
                'start_time.required' => __('closing_hours.validation.start_time_required'),
                'start_time.date_format' => __('closing_hours.validation.start_time_format'),
                'end_time.required' => __('closing_hours.validation.end_time_required'),
                'end_time.date_format' => __('closing_hours.validation.end_time_format'),
                'end_time.after' => __('closing_hours.validation.end_time_after'),
            ]
        );

        // Validate that the date has opening hours
        $dayOfWeek = Carbon::parse($data['date'])->englishDayOfWeek;
        $openingHour = OpeningHour::where('day', $dayOfWeek)->first();

        if (!$openingHour) {
            return back()->withInput($request->input())
                ->withErrors(['date' => __('closing_hours.validation.no_opening_hours')]);
        }

        // Validate that times are within opening hours
        $startTime = Carbon::parse($data['start_time'])->format('H:i:s');
        $endTime = Carbon::parse($data['end_time'])->format('H:i:s');

        if (
            $startTime < $openingHour->opening_time
            || $startTime >= $openingHour->closing_time
            || $endTime > $openingHour->closing_time
            || $endTime <= $openingHour->opening_time
        ) {
            return back()->withInput($request->input())
                ->withErrors(['start_time' => __('closing_hours.validation.outside_opening_hours')]);
        }

        // Check if a closing hour for this date already exists (excluding current)
        $existingClosingHour = ClosingHour::where('date', $data['date'])
            ->where('id', '!=', $closingHour->id)
            ->first();

        if ($existingClosingHour) {
            return back()->withInput($request->input())
                ->withErrors(['date' => __('closing_hours.validation.date_unique')]);
        }

        // Update Closing Hour
        $closingHour->update($data);

        return to_route('admin.closing-hours.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('closing_hours.updated'),
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClosingHour $closingHour)
    {
        // Delete Closing Hour
        $closingHour->delete();

        return to_route('admin.closing-hours.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('closing_hours.deleted'),
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Get available times based on opening hours for a specific day
     */
    private function getAvailableTimes($dayOfWeek)
    {
        $openingHour = OpeningHour::where('day', $dayOfWeek)->first();

        if (!$openingHour) {
            return [];
        }

        $timeArray = [];
        $start = Carbon::parse($openingHour->opening_time);
        $end = Carbon::parse($openingHour->closing_time);
        $step = 30; // 30 minutes intervals

        while ($start < $end) {
            $timeArray[] = [
                'value' => $start->format('H:i'),
                'text' => $start->format('H:i'),
            ];
            $start->addMinutes($step);
        }

        return $timeArray;
    }

    /**
     * API endpoint to get available times for a specific date
     */
    public function getTimesForDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $dayOfWeek = Carbon::parse($request->date)->englishDayOfWeek;
        $timeArray = $this->getAvailableTimes($dayOfWeek);

        return response()->json($timeArray);
    }
}
