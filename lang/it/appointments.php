<?php

return [
    // Titoli e navigazione
    'title' => 'Appuntamenti',
    'add' => 'Aggiungi Appuntamento',
    'edit' => 'Modifica Appuntamento',
    'trash' => 'Cestino Appuntamenti',
    'calendar' => 'Calendario',

    // Tabella
    'client' => 'Cliente',
    'service' => 'Servizio',
    'services' => 'Servizi',
    'date' => 'Data',
    'start_time' => 'Ora Inizio',
    'end_time' => 'Ora Fine',
    'notes' => 'Note',
    'actions' => 'Azioni',

    // Form
    'choose_client' => '-- Seleziona un Cliente --',
    'choose_service' => '-- Seleziona un Servizio --',
    'choose_date' => 'Seleziona una data',
    'start_time_label' => 'Ora Inizio',
    'end_time_label' => 'Ora Fine',
    'notes_placeholder' => 'Inserisci le note dell\'appuntamento...',

    // Messaggi
    'no_appointments' => 'Nessun Appuntamento Trovato',
    'created' => 'Appuntamento aggiunto con successo.',
    'updated' => 'Appuntamento aggiornato con successo.',
    'deleted' => 'Appuntamento eliminato.',
    'restored' => 'Appuntamento ripristinato con successo.',
    'permanent_delete' => 'Appuntamento eliminato definitivamente.',
    'marked_as_missed' => 'Appuntamento segnato come mancato.',
    'unmarked_as_missed' => 'Appuntamento non più segnato come mancato.',
    'cannot_mark_missed' => 'Impossibile segnare questo appuntamento come mancato.',
    'cannot_unmark_overlapping' => 'Impossibile riabilitare questo appuntamento perché si sovrappone con altri appuntamenti.',
    'user_blocked_due_to_misses' => 'L\'utente è stato bloccato per aver mancato 3 appuntamenti.',
    'cannot_edit_past' => 'Impossibile modificare un appuntamento già passato.',

    // Azioni
    'mark_as_missed' => 'Segna come Mancato',
    'unmark_as_missed' => 'Rimuovi Segno Mancato',
    'mark_missed_confirm' => 'Sei sicuro di voler segnare ":name" come mancato?',
    'unmark_missed_confirm' => 'Sei sicuro di voler rimuovere il segno di mancato da ":name"?',

    // Validazione
    'validation' => [
        'client_required' => 'Il cliente è obbligatorio',
        'client_exists' => 'Questo cliente non esiste',
        'service_required' => 'Il servizio è obbligatorio',
        'service_exists' => 'Questo servizio non esiste',
        'start_time_required' => 'L\'ora di inizio è obbligatoria',
        'start_time_format' => 'Inserisci un orario valido',
        'start_time_after' => 'L\'ora di inizio deve essere successiva all\'ora attuale',
        'date_required' => 'La data è obbligatoria',
        'date_format' => 'Inserisci una data valida',
        'date_after' => 'La data deve essere uguale o successiva alla data odierna',
        'date_beyond_next_month' => 'La data selezionata non può essere oltre l\'ultimo giorno del mese successivo',
        'notes_string' => 'Le note devono essere una stringa',
    ],

    // Business logic
    'public_holiday' => 'Ciao, il :date è un giorno festivo!',
    'closing_day' => 'Ci dispiace ma :day è un giorno di chiusura!',
    'outside_hours' => 'Ciao, questo appuntamento è fuori dal nostro orario di lavoro dalle :start alle :end!',
    'break_time' => 'Ciao, questo appuntamento si sovrappone alla nostra pausa dalle :start alle :end!',
    'closing_hour' => 'Ci dispiace ma questo appuntamento si sovrappone con un orario di chiusura straordinaria in questa data!',
    'already_exists' => 'Questo appuntamento esiste già',

    // Conferme
    'confirm_delete' => 'Sei sicuro di voler eliminare questo appuntamento?',
    'confirm_restore' => 'Vuoi ripristinare questo appuntamento?',
    'confirm_permanent_delete' => 'Sei sicuro? Questa azione è irreversibile!',
];
