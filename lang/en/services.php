<?php

return [
    // Titles
    'title' => 'Services',
    'add' => 'Add Service',
    'edit' => 'Edit Service',

    // Table
    'name' => 'Name',
    'category' => 'Category',
    'duration' => 'Duration',
    'price' => 'Price',
    'availability' => 'Availability',
    'actions' => 'Actions',

    // Form
    'name_label' => 'Service Name',
    'name_placeholder' => 'Enter service name',
    'category_label' => 'Category',
    'choose_category' => '-- Choose a Category --',
    'duration_label' => 'Duration',
    'duration_placeholder' => 'e.g. 01:30',
    'price_label' => 'Price',
    'price_placeholder' => 'e.g. 50.00',
    'availability_label' => 'Available',

    // Status
    'available' => 'Available',
    'unavailable' => 'Unavailable',

    // Messages
    'no_services' => 'No Services Found',
    'created' => 'Service created successfully.',
    'updated' => 'Service updated successfully.',
    'deleted' => 'Service deleted.',

    // Validation
    'validation' => [
        'name_required' => 'The service name is required',
        'name_string' => 'The service name must be a string',
        'name_unique' => 'This service name already exists',
        'category_required' => 'The category is required',
        'category_exists' => 'The selected category is invalid',
        'duration_required' => 'The duration is required',
        'duration_format' => 'Please insert a valid time format',
        'price_required' => 'The price is required',
        'price_decimal' => 'Please insert a valid price',
        'availability_required' => 'The availability is required',
        'availability_boolean' => 'The availability must be a boolean value',
    ],
];
