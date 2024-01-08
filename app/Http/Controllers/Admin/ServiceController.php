<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Create empty resource
        $service = new Service();
        return view('admin.services.create', compact('service'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|unique:services',
                'duration' => 'required|date_format:H:i:s',
                'is_available' => 'required|boolean',
            ],
            [
                'name.required' => 'The service name is required',
                'name.string' => 'The service name must be a string',
                'name.unique' => 'This service name already exists',

                'duration.required' => 'The duration is required',
                'duration.date_format' => 'Please insert a valid time format',

                'is_available.required' => 'The availability is required',
                'is_available.boolean' => 'The availability must be a boolean value',
            ]
        );

        // Insert Service
        $service = new Service();
        $service->fill($data);
        $service->save();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        // Validation
        $data = $request->validate(
            [
                'name' => ['required', 'string', Rule::unique('services')->ignore($service->id)],
                'duration' => 'required|date_format:H:i:s',
                'is_available' => 'required|boolean',
            ],
            [
                'name.required' => 'The service name is required',
                'name.string' => 'The service name must be a string',
                'name.unique' => 'This service name already exists',

                'duration.required' => 'The duration is required',
                'duration.date_format' => 'Please insert a valid time format',

                'is_available.required' => 'The availability is required',
                'is_available.boolean' => 'The availability must be a boolean value',
            ]
        );

        // Update Service
        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => 'Service updated successfully.',
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
