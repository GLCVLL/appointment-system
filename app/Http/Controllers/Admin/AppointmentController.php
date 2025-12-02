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
    public function index(Request $request)
    {
        $query = Appointment::with(['user']);

        if ($request->has('from_date') && $request->from_date) {
            $query->where('date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->where('date', '<=', $request->to_date);
        }

        if ($request->has('name') && $request->name) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        $appointments = $query->orderBy('date', 'desc')->orderBy('start_time')->paginate(10);

        $appointments->appends(['from_date' => $request->from_date, 'to_date' => $request->to_date, 'name' => $request->name]);

        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get Empty Appointment
        $appointment = new Appointment();

        // Get user and services (exclude blocked users)
        $users = User::where('blocked', false)->get();
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
                    'required',
                    'date_format:H:i',
                    function ($attribute, $value, $fail) {
                        $selectedDate = request('date');
                        $currentTime = date('H:i');
                        if ($selectedDate == date('Y-m-d') && $value < $currentTime) {
                            $fail('The Start Time must be a time after the current time.');
                        }
                    },
                ],
                'notes' => 'nullable|string',
            ],
            [
                'user_id.required' => __('appointments.validation.client_required'),
                'user_id.exists' => __('appointments.validation.client_exists'),

                'services.required' => __('appointments.validation.service_required'),
                'services.exists' => __('appointments.validation.service_exists'),

                'start_time.required' => __('appointments.validation.start_time_required'),
                'start_time.date_format' => __('appointments.validation.start_time_format'),

                'date.date' => __('appointments.validation.date_format'),
                'date.required' => __('appointments.validation.date_required'),

                'notes.string' => __('appointments.validation.notes_string'),
            ]
        );

        // Calculate end time
        $data['end_time'] = $this->calculateEndTime($data['start_time'], $data['services']);

        // Appointment Validation
        $errorMessage = '';


        // Check public koliday
        $appointmentDate = Carbon::parse($data['date']);

        $isPublicHolidays = ClosedDay::whereMonth('date', $appointmentDate->month)->whereDay('date', $appointmentDate->day)->first();

        if ($isPublicHolidays) $errorMessage = __('appointments.public_holiday', ['date' => $appointmentDate->format('d F')]);


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
                $errorMessage = __('appointments.closing_day', ['day' => $dayOfWeek]);
            }
            // if we are in working hours
            elseif (
                $startTime < $openingHour->opening_time
                || $startTime > $openingHour->closing_time
                || $endTime > $openingHour->closing_time
            ) {
                $work_start = date('H:i', strtotime($openingHour->opening_time));
                $work_end = date('H:i', strtotime($openingHour->closing_time));
                $errorMessage = __('appointments.outside_hours', ['start' => $work_start, 'end' => $work_end]);
            }
            // if we overllapping break time
            elseif (
                $openingHour->break_end != null &&
                $startTime < $openingHour->break_end && $endTime > $openingHour->break_start
            ) {
                $break_start = date('H:i', strtotime($openingHour->break_start));
                $break_end = date('H:i', strtotime($openingHour->break_end));
                $errorMessage = __('appointments.break_time', ['start' => $break_start, 'end' => $break_end]);
            }
        }


        // Check appointments overlapping
        if (!$errorMessage) {

            $overlappingAppointments = Appointment::where('date', $data['date'])
                ->where('missed', false)
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->count();

            // Set Error Message
            if ($overlappingAppointments) $errorMessage = __('appointments.already_exists');
        }


        // Return if error occurred
        if ($errorMessage) {
            return back()->withInput($request->input())
                ->with('messages', [
                    [
                        'sender' => 'System',
                        'content' => $errorMessage,
                        'timestamp' => now()
                    ]
                ])
                ->with('modal-error', true);
        }


        // Insert appointments
        $appointment = new Appointment();
        $appointment->fill($data);
        $appointment->save();

        if (Arr::exists($data, 'services')) $appointment->services()->attach($data['services']);


        // Check back loop
        $is_back_loop = url()->previous() === route('admin.appointments.create');
        $redirect_header = $is_back_loop ? redirect()->route('admin.appointments.index') : back();


        return $redirect_header
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('appointments.created'),
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
        // Get users (exclude blocked users, but include the current appointment's user if blocked)
        $users = User::where(function ($query) use ($appointment) {
            $query->where('blocked', false)
                ->orWhere('id', $appointment->user_id);
        })->get();
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
                    'required',
                    'date_format:H:i',
                    function ($attribute, $value, $fail) {
                        $selectedDate = request('date');
                        $currentTime = date('H:i');
                        if ($selectedDate == date('Y-m-d') && $value < $currentTime) {
                            $fail('The Start Time must be a time after the current time.');
                        }
                    },
                ],
                'notes' => 'nullable|string',
            ],
            [
                'user_id.required' => __('appointments.validation.client_required'),
                'user_id.exists' => __('appointments.validation.client_exists'),

                'services.required' => __('appointments.validation.service_required'),
                'services.*.exists' => __('appointments.validation.service_exists'),

                'date.required' => __('appointments.validation.date_required'),
                'date.date' => __('appointments.validation.date_format'),

                'start_time.required' => __('appointments.validation.start_time_required'),
                'start_time.date_format' => __('appointments.validation.start_time_format'),

                'notes.string' => __('appointments.validation.notes_string'),
            ]
        );

        // Check if appointment is in the past (cannot edit past appointments)
        $appointmentDateTime = Carbon::parse($appointment->date . ' ' . $appointment->end_time);
        if ($appointmentDateTime->isPast()) {
            return back()->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('appointments.cannot_edit_past'),
                    'timestamp' => now()
                ]
            ]);
        }

        $errorMessage = '';

        // Calculate end time
        $data['end_time'] = $this->calculateEndTime($data['start_time'], $data['services']);

        // Check if is a public Holiday
        $appointmentDate = Carbon::parse($data['date']);

        $isPublicHolidays = ClosedDay::whereMonth('date', $appointmentDate->month)->whereDay('date', $appointmentDate->day)->first();

        if ($isPublicHolidays) {

            $errorMessage = __('appointments.public_holiday', ['date' => $appointmentDate->format('d F')]);
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
                $errorMessage = __('appointments.closing_day', ['day' => $dayOfWeek]);
            }
            // if we are in working hours
            elseif (
                $startTime < $openingHour->opening_time
                || $startTime > $openingHour->closing_time
                || $endTime > $openingHour->closing_time
            ) {
                $work_start = date('H:i', strtotime($openingHour->opening_time));
                $work_end = date('H:i', strtotime($openingHour->closing_time));
                $errorMessage = __('appointments.outside_hours', ['start' => $work_start, 'end' => $work_end]);
            }
            // if we overllapping break time
            elseif (
                $openingHour->break_end != null &&
                $startTime < $openingHour->break_end && $endTime > $openingHour->break_start
            ) {
                $break_start = date('H:i', strtotime($openingHour->break_start));
                $break_end = date('H:i', strtotime($openingHour->break_end));
                $errorMessage = __('appointments.break_time', ['start' => $break_start, 'end' => $break_end]);
            }
        }

        if (!$errorMessage) {

            // Overlapping appointments checking
            $overlappingAppointments = Appointment::where('date', $data['date'])
                ->where('id', '!=', $appointment->id)
                ->where('missed', false)
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->count();
            // Throw Exception Overlapping appointments
            if ($overlappingAppointments) {

                $errorMessage = __('appointments.already_exists');
            }
        }

        if ($errorMessage) {
            return back()->withInput($request->input())->with('messages', [
                [
                    'sender' => 'System',
                    'content' => $errorMessage,
                    'timestamp' => now()
                ]
            ])
                ->with('modal-error', true)
                ->with('resource-id', $appointment->id);
        }

        $appointment->fill($data);
        $appointment->save();

        $appointment->services()->sync($data['services']);

        // Check back loop
        $is_back_loop = url()->previous() === route('admin.appointments.edit', $appointment);
        $redirect_header = $is_back_loop ? redirect()->route('admin.appointments.index') : back();


        return $redirect_header
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('appointments.updated'),
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

        return back()
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('appointments.deleted'),
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Trashed Appointments list
     */
    public function trash()
    {
        $appointments = Appointment::onlyTrashed()->paginate(10);

        return view('admin.appointments.trash', compact('appointments'));
    }

    /**
     * Delete permanently the given Appointment
     */
    public function drop(string $id)
    {
        $appointments = Appointment::onlyTrashed()->findOrFail($id);

        $appointments->forceDelete();

        return to_route('admin.appointments.trash')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => __('appointments.permanent_delete'),
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Delete permanently given Appointments
     */
    public function dropAll()
    {
        $total = Appointment::onlyTrashed()->paginate(10);

        Appointment::onlyTrashed()->forceDelete();

        return to_route('admin.appointments.trash')
            ->with('messages', [
                [
                    'sender' => 'System',
                    'content' => "$total Appointments deleted.",
                    'timestamp' => now()
                ]
            ]);
    }

    /**
     * Toggle appointment missed status
     */
    public function markAsMissed(Appointment $appointment)
    {
        $wasBlocked = $appointment->user->blocked;

        if ($appointment->missed) {
            // Check for overlapping appointments before unmarking as missed
            $overlappingAppointments = Appointment::where('date', $appointment->date)
                ->where('id', '!=', $appointment->id)
                ->where('missed', false)
                ->where('start_time', '<', $appointment->end_time)
                ->where('end_time', '>', $appointment->start_time)
                ->count();

            if ($overlappingAppointments > 0) {
                return back()->with('messages', [
                    [
                        'sender' => 'System',
                        'content' => __('appointments.cannot_unmark_overlapping'),
                        'timestamp' => now()
                    ]
                ]);
            }

            // Unmark as missed
            $appointment->missed = false;
            $appointment->save();

            // Decrement user's missed appointments counters
            $appointment->user->decrementMissedAppointment();

            $message = __('appointments.unmarked_as_missed');
        } else {
            // Mark as missed
            $appointment->missed = true;
            $appointment->save();

            // Increment user's missed appointments counters
            $appointment->user->incrementMissedAppointment();

            // Refresh to get updated blocked status
            $appointment->user->refresh();

            $message = __('appointments.marked_as_missed');
            if (!$wasBlocked && $appointment->user->blocked) {
                $message .= ' ' . __('appointments.user_blocked_due_to_misses');
            }
        }

        return back()->with('messages', [
            [
                'sender' => 'System',
                'content' => $message,
                'timestamp' => now()
            ]
        ]);
    }

    /**
     * Calculate the end time based on start time and selected services
     */
    private function calculateEndTime(string $startTime, array $serviceIds): string
    {
        $serviceDurations = Service::whereIn('id', $serviceIds)->pluck('duration')->toArray();
        $serviceDurations = array_map(function ($duration) {
            return Carbon::createFromTimeString($duration)->secondsSinceMidnight() / 60;
        }, $serviceDurations);
        $endTime = Carbon::parse($startTime)->addMinutes(array_sum($serviceDurations));

        return $endTime->format('H:i');
    }
}
