<?php

return [
    // Titles
    'title' => 'Closing Hours',
    'add' => 'Add Closing Hour',
    'edit' => 'Edit Closing Hour',

    // Table
    'date' => 'Date',
    'start_time' => 'Start Time',
    'end_time' => 'End Time',
    'actions' => 'Actions',

    // Form
    'date_label' => 'Date',
    'date_note' => 'Select a specific date for the closing hour. Available times will be based on the opening hours for that day.',
    'start_time_label' => 'Start Time',
    'end_time_label' => 'End Time',
    'closing_period' => 'Closing Period',

    // Messages
    'no_closing_hours' => 'No Closing Hours Found',
    'created' => 'Closing hour added successfully.',
    'updated' => 'Closing hour updated successfully.',
    'deleted' => 'Closing hour deleted.',

    // Validation
    'validation' => [
        'date_required' => 'The date is required',
        'date_format' => 'Insert a valid date',
        'date_future' => 'The date must be today or in the future',
        'start_time_required' => 'The start time is required',
        'start_time_format' => 'Insert a valid time',
        'end_time_required' => 'The end time is required',
        'end_time_format' => 'Insert a valid time',
        'end_time_after' => 'The end time must be after the start time',
        'no_opening_hours' => 'No opening hours found for this day',
        'outside_opening_hours' => 'The closing hours must be within the opening hours for this day',
        'overlapping_hours' => 'This closing hour overlaps with another closing hour on the same date',
    ],
];
