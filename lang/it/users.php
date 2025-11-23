<?php

return [
    // Titoli
    'title' => 'Utenti',
    'clients' => 'Clienti',
    'add' => 'Aggiungi Utente',
    'edit' => 'Modifica Utente',

    // Tabella
    'name' => 'Nome',
    'email' => 'Email',
    'phone' => 'Telefono',
    'role' => 'Ruolo',
    'registered' => 'Registrato',
    'actions' => 'Azioni',

    // Ruoli
    'admin' => 'Amministratore',
    'user' => 'Utente',
    'client' => 'Cliente',

    // Form
    'name_label' => 'Nome',
    'name_placeholder' => 'Inserisci il nome',
    'email_label' => 'Email',
    'email_placeholder' => 'Inserisci l\'email',
    'phone_label' => 'Telefono',
    'phone_placeholder' => 'Inserisci il telefono',
    'password_label' => 'Password',
    'password_placeholder' => 'Inserisci la password',
    'role_label' => 'Ruolo',
    'choose_role' => '-- Seleziona un Ruolo --',

    // Messaggi
    'no_users' => 'Nessun Utente Trovato',
    'created' => 'Utente creato con successo.',
    'updated' => 'Utente aggiornato con successo.',
    'deleted' => 'Utente eliminato.',

    // Validazione
    'validation' => [
        'name_required' => 'Il nome è obbligatorio',
        'name_string' => 'Il nome deve essere una stringa',
        'name_max' => 'Il nome non può superare i 255 caratteri',
        'email_required' => 'L\'email è obbligatoria',
        'email_email' => 'Inserisci un\'email valida',
        'email_max' => 'L\'email non può superare i 255 caratteri',
        'email_unique' => 'Questa email esiste già',
        'password_required' => 'La password è obbligatoria',
        'password_string' => 'La password deve essere una stringa',
        'password_min' => 'La password deve essere di almeno 8 caratteri',
        'phone_string' => 'Il numero di telefono deve essere valido',
    ],
];
