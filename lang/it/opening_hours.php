<?php

return [
    // Titoli
    'title' => 'Orari di Apertura',
    'add' => 'Aggiungi Orario',
    'edit' => 'Modifica Orario',

    // Tabella
    'day' => 'Giorno',
    'work_start' => 'Inizio Lavoro',
    'work_end' => 'Fine Lavoro',
    'break_start' => 'Inizio Pausa',
    'break_end' => 'Fine Pausa',
    'is_closed' => 'Chiuso',
    'actions' => 'Azioni',

    // Form
    'day_label' => 'Giorno',
    'choose_day' => '-- Seleziona un Giorno --',
    'work_start_label' => 'Inizio Lavoro',
    'work_end_label' => 'Fine Lavoro',
    'break_start_label' => 'Inizio Pausa',
    'break_end_label' => 'Fine Pausa',
    'is_closed_label' => 'Giorno di Chiusura',

    // Status
    'open' => 'Aperto',
    'closed' => 'Chiuso',

    // Messaggi
    'no_hours' => 'Nessun Orario Trovato',
    'created' => 'Orario creato con successo.',
    'updated' => 'Orario aggiornato con successo.',
    'deleted' => 'Orario eliminato.',

    // Form aggiuntivi
    'working_time' => 'Orario di Lavoro',
    'start' => 'Inizio',
    'end' => 'Fine',
    'break_time' => 'Pausa',
    'none' => 'nessuna',

    // Validazione
    'validation' => [
        'day_required' => 'Il giorno è obbligatorio',
        'day_string' => 'Il giorno deve essere una stringa',
        'day_unique' => 'Questo giorno esiste già',
        'opening_time_required' => 'L\'orario di apertura è obbligatorio',
        'opening_time_format' => 'Inserisci un orario valido',
        'closing_time_required' => 'L\'orario di chiusura è obbligatorio',
        'closing_time_format' => 'Inserisci un orario valido',
        'break_start_format' => 'Inserisci un orario valido',
        'break_end_format' => 'Inserisci un orario valido',
    ],
];
