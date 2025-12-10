<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Booking Interval
    |--------------------------------------------------------------------------
    |
    | This value determines the time interval (in minutes) used for generating
    | available appointment slots. This interval is used both in the backend
    | (for API responses) and frontend (for UI time selectors).
    |
    | Common values:
    | - 15 minutes: More granular slots, allows more precise bookings
    | - 30 minutes: Standard interval (default)
    | - 60 minutes: Hourly slots, simpler but less flexible
    |
    | Note: Changing this value will affect how time slots are generated,
    | but existing appointments will continue to work regardless of the
    | interval value.
    |
    */

    'booking_interval_minutes' => (int) env('APPOINTMENT_BOOKING_INTERVAL', 30),

];
