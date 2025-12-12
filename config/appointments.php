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

    /*
    |--------------------------------------------------------------------------
    | Cancellation Time Limit
    |--------------------------------------------------------------------------
    |
    | This value determines the minimum time (in hours) before an appointment
    | start time that a user can cancel the appointment. For example, if set
    | to 24, users can only cancel appointments that are at least 24 hours
    | in the future.
    |
    | Common values:
    | - 1 hour: Very flexible cancellation policy
    | - 24 hours: Standard cancellation policy (default)
    | - 48 hours: More restrictive cancellation policy
    |
    | Note: This only applies to user-initiated cancellations via the API.
    | Admin users can cancel appointments at any time.
    |
    */

    'cancellation_hours_before' => (int) env('APPOINTMENT_CANCELLATION_HOURS_BEFORE', 24),

];
