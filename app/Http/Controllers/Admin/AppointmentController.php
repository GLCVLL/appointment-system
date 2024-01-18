<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ClosedDay;
use App\Models\OpeningHour;
use App\Models\Service;
use App\Models\User;
use App\View\Components\AppLayout;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['user'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(10);

        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get Empty Appointment
        $appointment = new Appointment();

        // Get user and services
        $users = User::all();
        $services = Service::all();

        // Create time array
        $start = Carbon::createFromTimeString('00:00');
        $end = Carbon::createFromTimeString('23:30');
        $interval = 'PT30M'; // Period Time di 30 minuti
        $period = new CarbonPeriod($start, $interval, $end);
        $time_array = [];

        foreach ($period as $date) {
            $time_array[] = [
                'value' => $date->format('H:i'),
                'text' => $date->format('H:i'),
            ];
        }

        // Get valid appointments
        $appointments = Appointment::where('date', '>=', date('Y-m-d'))->get();

        // Get closed days
        $closedDays = ClosedDay::pluck('date')->toArray();

        // Get opening hours
        $openingHours = OpeningHour::all();


        return view('admin.appointments.create', compact('appointment', 'users', 'services', 'time_array', 'appointments', 'closedDays', 'openingHours'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $data = $request->validate(
            [
                'user_id' => 'required|exists:users,id',
                'services' => 'required|exists:services,id',
                'date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
                'start_time' => [
                    'required', 'date_format:H:i',
                    function ($attribute, $value, $fail) {
                        $selectedDate = request('date');
                        $currentTime = date('H:i');
                        if ($selectedDate == date('Y-m-d') && $value < $currentTime) {
                            $fail('The Start Time must be a time after the current time.');
                        }
                    },
                ],
                'end_time' => 'required|date_format:H:i|after:start_time',
                'notes' => 'nullable|string',
            ],
            [
                'user_id.required' => 'The client is required',
                'user_id.exists' => 'This client already exists',

                'services.required' => 'The service is required',
                'services.exists' => 'This service already exists',

                'start_time.required' => 'The start Time is required',
                'start_time.date_format' => 'Insert a valide time',

                'end_time.required' => 'The end Time is required',
                'end_time.date_format' => 'Insert a valide time',

                'date.date' => 'Insert a valide date',
                'date.required' => 'The date is required',

                'notes.string' => 'The notes must be a string',
            ]
        );


        // Appointment Validation
        $errorMessage = '';


        // Check public koliday
        $appointmentDate = Carbon::parse($data['date']);

        $isPublicHolidays = ClosedDay::whereMonth('date', $appointmentDate->month)->whereDay('date', $appointmentDate->day)->first();

        if ($isPublicHolidays) $errorMessage = "Hi there, {$appointmentDate->format('d F')} is a public Holiday!";


        // Check opening hours
        if (!$errorMessage) {

            // all variables
            $dayOfWeek = Carbon::parse($data['date'])->englishDayOfWeek;
            $startTime = Carbon::parse($data['start_time'])->format('H:i:s');
            $endTime = Carbon::parse($data['end_time'])->format('H:i:s');

            // Get Opening Hours current day
            $openingHour = OpeningHour::where('day', $dayOfWeek)->first();


            // if is a closing day or not
            if (!$openingHour) {
                $errorMessage = "I'm sorry but $dayOfWeek is a closing day!";
            }
            // if we are in working hours
            elseif (
                $startTime < $openingHour->opening_time
                || $startTime > $openingHour->closing_time
                || $endTime > $openingHour->closing_time
            ) {
                $work_start = date('H:i', strtotime($openingHour->opening_time));
                $work_end = date('H:i', strtotime($openingHour->closing_time));
                $errorMessage = "Hi there, this appointment is outside of our working hours from $work_start to $work_end!";
            }
            // if we overllapping break time
            elseif (
                $openingHour->break_end != null &&
                $startTime < $openingHour->break_end && $endTime > $openingHour->break_start
            ) {
                $break_start = date('H:i', strtotime($openingHour->break_start));
                $break_end = date('H:i', strtotime($openingHour->break_end));
                $errorMessage = "Hi there, this appointment overlaps our breaking time from $break_start to $break_end!";
            }
        }


        // Check appointments overlapping
        if (!$errorMessage) {

            $overlappingAppointments = Appointment::where('date', $data['date'])
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->count();

            // Set Error Message   
            if ($overlappingAppointments) $errorMessage = 'This appointment already exists';
        }


        // Return if error occurred
        if ($errorMessage) {
            return back()->withInput($request->input())->with('messages', [
                [
                    'sender' => 'System',
                    'color' => 'danger',
                    'content' => $errorMessage,
                    'timestamp' => now()
                ]
            ]);
        }


        // Insert appointments
        $appointment = new Appointment();
        $appointment->fill($data);
        $appointment->save();

        if (Arr::exists($data, 'services')) $appointment->services()->attach($data['services']);


        return to_route('admin.appointments.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => 'Appointment added successfully.',
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $users = User::all();
        $services = Service::all();

        $selectedServices = $appointment->services->pluck('id')->toArray();

        // Create time array
        $start = Carbon::createFromTimeString('00:00');
        $end = Carbon::createFromTimeString('23:30');
        $interval = 'PT30M'; // Period Time di 30 minuti
        $period = new CarbonPeriod($start, $interval, $end);
        $time_array = [];

        foreach ($period as $date) {
            $time_array[] = [
                'value' => $date->format('H:i'),
                'text' => $date->format('H:i'),
            ];
        }

        // Get valid appointments
        $appointments = Appointment::where('date', '>=', date('Y-m-d'))->get();

        // Get closed days
        $closedDays = ClosedDay::pluck('date')->toArray();

        // Get opening hours
        $openingHours = OpeningHour::all();

        return view('admin.appointments.edit', compact('appointment', 'users', 'services', 'selectedServices', 'time_array', 'appointments', 'closedDays', 'openingHours'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Validation
        $data = $request->validate(
            [
                'user_id' => 'required|exists:users,id',
                'services' => 'required|array',
                'services.*' => 'exists:services,id',
                'date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
                'start_time' => [
                    'required', 'date_format:H:i',
                    function ($attribute, $value, $fail) {
                        $selectedDate = request('date');
                        $currentTime = date('H:i');
                        if ($selectedDate == date('Y-m-d') && $value < $currentTime) {
                            $fail('The Start Time must be a time after the current time.');
                        }
                    },
                ],
                'end_time' => 'required|date_format:H:i|after:start_time',
                'notes' => 'nullable|string',
            ],
            [
                'user_id.required' => 'The client is required',
                'user_id.exists' => 'This client does not exist',

                'services.required' => 'At least one service is required',
                'services.*.exists' => 'This service does not exist',

                'date.required' => 'The date is required',
                'date.date' => 'Please enter a valid date',

                'start_time.required' => 'The start time is required',
                'start_time.date_format' => 'Please enter a valid start time',

                'end_time.required' => 'The end time is required',
                'end_time.date_format' => 'Please enter a valid end time',
                'end_time.after' => 'The end time must be after the start time',

                'notes.string' => 'The notes must be a string',
            ]
        );

        $errorMessage = '';

        // Check if is a public Holiday
        $appointmentDate = Carbon::parse($data['date']);

        $isPublicHolidays = ClosedDay::whereMonth('date', $appointmentDate->month)->whereDay('date', $appointmentDate->day)->first();

        if ($isPublicHolidays) {

            $errorMessage = "Hi there, {$appointmentDate->format('d F')} is a public Holiday!";
        }

        if (!$errorMessage) {
            // check on Opening Hours

            // all variables

            $dayOfWeek = Carbon::parse($data['date'])->englishDayOfWeek;

            $startTime = Carbon::parse($data['start_time'])->format('H:i:s');
            $endTime = Carbon::parse($data['end_time'])->format('H:i:s');


            // Get Opening Hours current day

            $openingHour = OpeningHour::where('day', $dayOfWeek)->first();

            // if is a closing day or not

            if (!$openingHour) {
                $errorMessage = "I'm sorry but $dayOfWeek is a closing day!";
            }
            // if we are in working hours
            elseif (
                $startTime < $openingHour->opening_time
                || $startTime > $openingHour->closing_time
                || $endTime > $openingHour->closing_time
            ) {
                $work_start = date('H:i', strtotime($openingHour->opening_time));
                $work_end = date('H:i', strtotime($openingHour->closing_time));
                $errorMessage = "Hi there, this appointment is outside of our working hours from $work_start to $work_end!";
            }
            // if we overllapping break time
            elseif (
                $openingHour->break_end != null &&
                $startTime < $openingHour->break_end && $endTime > $openingHour->break_start
            ) {
                $break_start = date('H:i', strtotime($openingHour->break_start));
                $break_end = date('H:i', strtotime($openingHour->break_end));
                $errorMessage = "Hi there, this appointment overlaps our breaking time from $break_start to $break_end!";
            }
        }

        if (!$errorMessage) {

            // Overlapping appointments checking
            $overlappingAppointments = Appointment::where('date', $data['date'])
                ->where('id', '!=', $appointment->id)
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->count();
            // Throw Exception Overlapping appointments    
            if ($overlappingAppointments) {

                $errorMessage = 'This appointment already exists';
            }
        }

        if ($errorMessage) {
            return back()->withInput($request->input())->with('messages', [
                [
                    'sender' => 'System',
                    'color' => 'danger',
                    'content' => $errorMessage,
                    'timestamp' => now()
                ]
            ]);
        }

        $appointment->fill($data);
        $appointment->save();

        $appointment->services()->sync($data['services']);

        return redirect()->route('admin.appointments.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'color' => 'success',
                    'content' => 'Appointment updated successfully.',
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        // Delete Appointment
        $appointment->delete();

        return to_route('admin.appointments.index')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => 'Appointment deleted.',
                    'timestamp' => now()
                ]
            ]);
    }
}
