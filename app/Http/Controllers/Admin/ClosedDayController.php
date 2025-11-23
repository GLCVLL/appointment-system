<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClosedDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClosedDayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $closedDays = ClosedDay::paginate(10);

        return view('admin.closed-days.index', compact('closedDays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Create empty resource
        $closedDay = new ClosedDay();

        return view('admin.closed-days.create', compact('closedDay'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $data = $request->validate(
            [
                'date' => 'required|date',
            ],
            [
                'date.required' => 'The date is required',
                'date.date' => 'Insert a valide date',
            ]
        );

        // Normalize date to year 2000 (year is ignored in validation)
        $parsedDate = Carbon::parse($data['date']);
        $normalizedDate = Carbon::create(2000, $parsedDate->month, $parsedDate->day)->format('Y-m-d');

        // Check if a closed day with same month/day already exists
        $existingClosedDay = ClosedDay::whereMonth('date', $parsedDate->month)
            ->whereDay('date', $parsedDate->day)
            ->first();

        if ($existingClosedDay) {
            return back()->withInput($request->input())
                ->withErrors(['date' => 'A closed day for this date already exists (year is ignored).']);
        }

        // Insert Closed Day with normalized date
        $closedDay = new ClosedDay();
        $closedDay->date = $normalizedDate;
        $closedDay->save();


        return to_route('admin.closed-days.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => 'Closed Day added successfully.',
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClosedDay $closedDay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClosedDay $closedDay)
    {
        return view('admin.closed-days.edit', compact('closedDay'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClosedDay $closedDay)
    {
        // Validation
        $data = $request->validate(
            [
                'date' => 'required|date',
            ],
            [
                'date.required' => 'The date is required',
                'date.date' => 'Insert a valide date',
            ]
        );

        // Normalize date to year 2000 (year is ignored in validation)
        $parsedDate = Carbon::parse($data['date']);
        $normalizedDate = Carbon::create(2000, $parsedDate->month, $parsedDate->day)->format('Y-m-d');

        // Check if a closed day with same month/day already exists (excluding current)
        $existingClosedDay = ClosedDay::whereMonth('date', $parsedDate->month)
            ->whereDay('date', $parsedDate->day)
            ->where('id', '!=', $closedDay->id)
            ->first();

        if ($existingClosedDay) {
            return back()->withInput($request->input())
                ->withErrors(['date' => 'A closed day for this date already exists (year is ignored).']);
        }

        // Update Closed Day with normalized date
        $closedDay->date = $normalizedDate;
        $closedDay->save();


        return to_route('admin.closed-days.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => 'Closed Day updated successfully.',
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClosedDay $closedDay)
    {
        // Delete Closed Day
        $closedDay->delete();

        return to_route('admin.closed-days.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => 'Closed Day deleted.',
                    'timestamp' => now()
                ]
            ]);
    }
}
