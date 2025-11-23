<?php

return [
    // Titles
    'title' => 'Opening Hours',
    'add' => 'Add Opening Hours',
    'edit' => 'Edit Opening Hours',

    // Table
    'day' => 'Day',
    'work_start' => 'Work Start',
    'work_end' => 'Work End',
    'break_start' => 'Break Start',
    'break_end' => 'Break End',
    'is_closed' => 'Closed',
    'actions' => 'Actions',

    // Form
    'day_label' => 'Day',
    'choose_day' => '-- Choose a Day --',
    'work_start_label' => 'Work Start',
    'work_end_label' => 'Work End',
    'break_start_label' => 'Break Start',
    'break_end_label' => 'Break End',
    'is_closed_label' => 'Closing Day',

    // Status
    'open' => 'Open',
    'closed' => 'Closed',

    // Messages
    'no_hours' => 'No Opening Hours Found',
    'created' => 'Opening hours created successfully.',
    'updated' => 'Opening hours updated successfully.',
    'deleted' => 'Opening hours deleted.',

    // Additional form
    'working_time' => 'Working Time',
    'start' => 'Start',
    'end' => 'End',
    'break_time' => 'Break Time',
    'none' => 'none',

    // Validation
    'validation' => [
        'day_required' => 'The day is required',
        'day_string' => 'The day must be a string',
        'day_unique' => 'This day already exists',
        'opening_time_required' => 'The opening time is required',
        'opening_time_format' => 'Insert a valid time',
        'closing_time_required' => 'The closing time is required',
        'closing_time_format' => 'Insert a valid time',
        'break_start_format' => 'Insert a valid time',
        'break_end_format' => 'Insert a valid time',
    ],
];
