<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ClosedDay;
use App\Models\OpeningHour;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function store(Request $request): Response
    {
        // Validation
        $data = $request->all();
        $validator = Validator::make(
            $data,
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
                'user_id.required' => 'The client is required',
                'user_id.exists' => 'This client does not exists',

                'services.required' => 'The service is required',
                'services.exists' => 'This service does not exists',

                'start_time.required' => 'The start Time is required',
                'start_time.date_format' => 'Insert a valide time',

                'date.date' => 'Insert a valide date',
                'date.required' => 'The date is required',

                'notes.string' => 'The notes must be a string',
            ]
        );

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 400);
        }

        // Calculate end time
        $data['end_time'] = $this->calculateEndTime($data['start_time'], $data['services']);


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
                ->where('missed', false)
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->count();

            // Set Error Message   
            if ($overlappingAppointments) $errorMessage = 'This appointment already exists';
        }


        // Return if error occurred
        if ($errorMessage) {
            return response(['appErrors' => $errorMessage], 400);
        }


        // Insert appointments
        $appointment = new Appointment();
        $appointment->fill($data);
        $appointment->save();

        if (Arr::exists($data, 'services')) $appointment->services()->attach($data['services']);

        return response($appointment, 201);
    }

    public function getBookingHours()
    {
        // Initialize arrays for storing dates and slots
        $date = [];
        $slotDays = [];

        // Define the start and end of the period for generating slots (one year from now)
        $start = Carbon::now();
        $end = Carbon::parse($start)->addYear();

        // Create a period to iterate over every day within a year
        $period = CarbonPeriod::create($start, $end);

        // Loop through each day in the period and add it to the date array
        foreach ($period as $day) {
            $date[] = $day->format('Y-m-d');
        }

        // Loop through each date and generate slots
        foreach ($date as $dayStr) {
            $day = Carbon::createFromFormat('Y-m-d', $dayStr);
            $dayOfWeek = $day->englishDayOfWeek;
            $openingHours = OpeningHour::where('day', $dayOfWeek)->first();

            // Check if opening hours are defined for the day
            if ($openingHours) {
                $slots = [];


                // Create a period for the working hours of the day
                $start = Carbon::createFromTimeString($openingHours->opening_time);
                $end = Carbon::createFromTimeString($openingHours->closing_time);
                $interval = 'PT30M'; // 30 minutes interval
                $period = new CarbonPeriod($start, $interval, $end);

                // Convert break start and end times to Carbon objects and adjust them
                $breakStartStr = $openingHours->break_start ? Carbon::createFromTimeString($openingHours->break_start)->subHour()->format('H:i') : null;
                $breakEndStr = $openingHours->break_end ? Carbon::createFromTimeString($openingHours->break_end)->format('H:i') : null;
                $closingTimeStr = $end->subHour()->format('H:i');

                // Loop through each half-hour slot in the working hours
                foreach ($period as $hour) {
                    $formatHour = $hour->format('H:i');

                    // Check if the slot is outside of the break time and within working hours
                    if ($formatHour <= $breakStartStr || ($formatHour >= $breakEndStr && $formatHour <= $closingTimeStr)) {
                        // Count overlapping appointments for the slot
                        $overlappingAppointmentsCount = Appointment::where('date', $dayStr)
                            ->where('missed', false)
                            ->where('start_time', '<=', $hour)
                            ->where('end_time', '>', $hour)
                            ->count();

                        // Determine the status of the slot
                        $status = $overlappingAppointmentsCount > 0 ? 'booked' : '';

                        // Add the slot to the slots array
                        $slots[] = [
                            'hour' => $formatHour,
                            'status' => $status
                        ];
                    }
                }

                // Add the day's slots to the slotDays array
                $slotDays[] = [
                    'date' => $dayStr,
                    'slots' => $slots,
                ];
            }
        }

        // Retrieve the list of closed days
        $closedDays = ClosedDay::pluck('date')->toArray();

        foreach ($closedDays as $closedDay) {

            $currentYear = Carbon::now()->format('Y');
            $nextYear = Carbon::parse($currentYear)->addYear()->format('Y');

            $formatClosedDay = Carbon::createFromFormat('Y-m-d', $closedDay)->format('-m-d');

            $closedDaysTwoYears[] = $currentYear . $formatClosedDay;

            $closedDaysTwoYears[] = $nextYear . $formatClosedDay;
        }

        // Prepare the working hours data for the response
        $workingHours = [
            'slotDays' => $slotDays,
            'closedDays' => $closedDaysTwoYears
        ];

        // Return the response with the working hours data
        return response($workingHours, 200);
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
