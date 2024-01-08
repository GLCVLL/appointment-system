<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClosedDay;
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
                'date' => 'required|date|unique:closed_days',
            ],
            [
                'date.required' => 'The date is required',
                'date.date' => 'Insert a valide date',
                'date.unique' => 'This date already exists',
            ]
        );


        // Insert Closed Day
        $closedDay = new ClosedDay();
        $closedDay->fill($data);
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
                'date' => ['required', 'date', Rule::unique('closed_days')->ignore($closedDay->id)],
            ],
            [
                'date.required' => 'The date is required',
                'date.date' => 'Insert a valide date',
                'date.unique' => 'This date already exists',
            ]
        );


        // Update Closed DAy
        $closedDay->update($data);


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
