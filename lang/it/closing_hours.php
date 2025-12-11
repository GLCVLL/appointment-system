<?php

return [
    // Titles
    'title' => 'Orari di Chiusura Straordinaria',
    'add' => 'Aggiungi Orario di Chiusura',
    'edit' => 'Modifica Orario di Chiusura',

    // Table
    'date' => 'Data',
    'start_time' => 'Ora Inizio',
    'end_time' => 'Ora Fine',
    'actions' => 'Azioni',

    // Form
    'date_label' => 'Data',
    'date_note' => 'Seleziona una data specifica per l\'orario di chiusura. Gli orari disponibili saranno basati sugli orari di apertura di quel giorno.',
    'start_time_label' => 'Ora Inizio',
    'end_time_label' => 'Ora Fine',
    'closing_period' => 'Periodo di Chiusura',

    // Messages
    'no_closing_hours' => 'Nessun Orario di Chiusura Trovato',
    'created' => 'Orario di chiusura aggiunto con successo.',
    'updated' => 'Orario di chiusura aggiornato con successo.',
    'deleted' => 'Orario di chiusura eliminato.',

    // Validation
    'validation' => [
        'date_required' => 'La data è obbligatoria',
        'date_format' => 'Inserisci una data valida',
        'date_future' => 'La data deve essere oggi o nel futuro',
        'start_time_required' => 'L\'ora di inizio è obbligatoria',
        'start_time_format' => 'Inserisci un orario valido',
        'end_time_required' => 'L\'ora di fine è obbligatoria',
        'end_time_format' => 'Inserisci un orario valido',
        'end_time_after' => 'L\'ora di fine deve essere dopo l\'ora di inizio',
        'no_opening_hours' => 'Nessun orario di apertura trovato per questo giorno',
        'outside_opening_hours' => 'Gli orari di chiusura devono essere compresi negli orari di apertura di questo giorno',
        'overlapping_hours' => 'Questo orario di chiusura si sovrappone con un altro orario di chiusura nella stessa data',
        'has_appointments' => 'Non è possibile creare un orario di chiusura che includa appuntamenti esistenti',
    ],
];
