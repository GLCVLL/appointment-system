<?php

return [
    // Titles and navigation
    'title' => 'Appointments',
    'add' => 'Add Appointment',
    'edit' => 'Edit Appointment',
    'trash' => 'Trash Appointments',
    'calendar' => 'Calendar',

    // Table
    'client' => 'Client',
    'service' => 'Service',
    'services' => 'Services',
    'date' => 'Date',
    'start_time' => 'Start Time',
    'end_time' => 'End Time',
    'notes' => 'Notes',
    'actions' => 'Actions',

    // Form
    'choose_client' => '-- Choose a Client --',
    'choose_service' => '-- Choose a Service --',
    'choose_date' => 'Choose a date',
    'start_time_label' => 'Start Time',
    'end_time_label' => 'End Time',
    'notes_placeholder' => 'Enter appointment notes...',

    // Messages
    'no_appointments' => 'No Appointments Found',
    'created' => 'Appointment added successfully.',
    'updated' => 'Appointment updated successfully.',
    'deleted' => 'Appointment deleted.',
    'restored' => 'Appointment restored successfully.',
    'permanent_delete' => 'Appointment permanently deleted.',
    'marked_as_missed' => 'Appointment marked as missed.',
    'unmarked_as_missed' => 'Appointment unmarked as missed.',
    'cannot_mark_missed' => 'Cannot mark this appointment as missed.',
    'cannot_unmark_overlapping' => 'Cannot unmark this appointment as missed because it overlaps with other appointments.',
    'user_blocked_due_to_misses' => 'User has been blocked due to 3 missed appointments.',
    'cannot_edit_past' => 'Cannot edit an appointment that has already passed.',

    // Actions
    'mark_as_missed' => 'Mark as Missed',
    'unmark_as_missed' => 'Unmark as Missed',
    'mark_missed_confirm' => 'Are you sure you want to mark ":name" as missed?',
    'unmark_missed_confirm' => 'Are you sure you want to unmark ":name" as missed?',

    // Validation
    'validation' => [
        'client_required' => 'The client is required',
        'client_exists' => 'This client does not exists',
        'service_required' => 'The service is required',
        'service_exists' => 'This service does not exists',
        'start_time_required' => 'The start Time is required',
        'start_time_format' => 'Insert a valid time',
        'start_time_after' => 'The Start Time must be a time after the current time.',
        'date_required' => 'The date is required',
        'date_format' => 'Insert a valid date',
        'date_after' => 'Date must be after or equal current date',
        'date_beyond_next_month' => 'The selected date cannot be beyond the last day of next month.',
        'notes_string' => 'The notes must be a string',
        'appointment_not_found' => 'Appointment not found or you do not have permission to delete it.',
        'cannot_delete_past' => 'Cannot delete an appointment that has already passed.',
        'cannot_delete_too_soon' => 'This appointment can only be cancelled at least :hours hours before the scheduled time.',
    ],

    // Business logic
    'public_holiday' => 'Hi there, :date is a public Holiday!',
    'closing_day' => 'I\'m sorry but :day is a closing day!',
    'outside_hours' => 'Hi there, this appointment is outside of our working hours from :start to :end!',
    'break_time' => 'Hi there, this appointment overlaps our breaking time from :start to :end!',
    'closing_hour' => 'I\'m sorry but this appointment overlaps with a closing hour on this date!',
    'already_exists' => 'This appointment already exists',

    // Confirmations
    'confirm_delete' => 'Are you sure you want to delete this appointment?',
    'confirm_restore' => 'Do you want to restore this appointment?',
    'confirm_permanent_delete' => 'Are you sure? This action is irreversible!',
];
