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

    // Validation
    'validation' => [
        'client_required' => 'The client is required',
        'client_exists' => 'This client already exists',
        'service_required' => 'The service is required',
        'service_exists' => 'This service already exists',
        'start_time_required' => 'The start Time is required',
        'start_time_format' => 'Insert a valid time',
        'date_required' => 'The date is required',
        'date_format' => 'Insert a valid date',
        'notes_string' => 'The notes must be a string',
    ],

    // Business logic
    'public_holiday' => 'Hi there, :date is a public Holiday!',
    'closing_day' => 'I\'m sorry but :day is a closing day!',
    'outside_hours' => 'Hi there, this appointment is outside of our working hours from :start to :end!',
    'break_time' => 'Hi there, this appointment overlaps our breaking time from :start to :end!',
    'already_exists' => 'This appointment already exists',

    // Confirmations
    'confirm_delete' => 'Are you sure you want to delete this appointment?',
    'confirm_restore' => 'Do you want to restore this appointment?',
    'confirm_permanent_delete' => 'Are you sure? This action is irreversible!',
];
