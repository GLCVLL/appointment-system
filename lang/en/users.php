<?php

return [
    // Titles
    'title' => 'Users',
    'clients' => 'Clients',
    'add' => 'Add User',
    'edit' => 'Edit User',

    // Table
    'name' => 'Name',
    'email' => 'Email',
    'phone' => 'Phone',
    'role' => 'Role',
    'registered' => 'Registered',
    'missed_count' => 'Missed Appointments',
    'actions' => 'Actions',

    // Roles
    'admin' => 'Administrator',
    'user' => 'User',
    'client' => 'Client',

    // Form
    'name_label' => 'Name',
    'name_placeholder' => 'Enter name',
    'email_label' => 'Email',
    'email_placeholder' => 'Enter email',
    'phone_label' => 'Phone',
    'phone_placeholder' => 'Enter phone',
    'password_label' => 'Password',
    'password_placeholder' => 'Enter password',
    'role_label' => 'Role',
    'choose_role' => '-- Choose a Role --',

    // Messages
    'no_users' => 'No Users Found',
    'created' => 'User created successfully.',
    'updated' => 'User updated successfully.',
    'deleted' => 'User deleted.',

    // Validation
    'validation' => [
        'name_required' => 'The name is required',
        'name_string' => 'The name must be a string',
        'name_max' => 'The name may not be greater than 255 characters',
        'email_required' => 'The email is required',
        'email_email' => 'Please insert a valid email',
        'email_max' => 'The email may not be greater than 255 characters',
        'email_unique' => 'This email already exists',
        'password_required' => 'The password is required',
        'password_string' => 'The password must be a string',
        'password_min' => 'The password must be at least 8 characters',
        'phone_string' => 'The phone number must be a correct number',
    ],
];
