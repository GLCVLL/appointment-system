<?php

return [
    // Titoli
    'title' => 'Servizi',
    'add' => 'Aggiungi Servizio',
    'edit' => 'Modifica Servizio',

    // Tabella
    'name' => 'Nome',
    'category' => 'Categoria',
    'duration' => 'Durata',
    'price' => 'Prezzo',
    'availability' => 'Disponibilità',
    'actions' => 'Azioni',

    // Form
    'name_label' => 'Nome Servizio',
    'name_placeholder' => 'Inserisci il nome del servizio',
    'category_label' => 'Categoria',
    'choose_category' => '-- Seleziona una Categoria --',
    'duration_label' => 'Durata',
    'duration_placeholder' => 'es. 01:30',
    'price_label' => 'Prezzo',
    'price_placeholder' => 'es. 50.00',
    'availability_label' => 'Disponibile',

    // Status
    'available' => 'Disponibile',
    'unavailable' => 'Non Disponibile',

    // Messaggi
    'no_services' => 'Nessun Servizio Trovato',
    'created' => 'Servizio creato con successo.',
    'updated' => 'Servizio aggiornato con successo.',
    'deleted' => 'Servizio eliminato.',

    // Validazione
    'validation' => [
        'name_required' => 'Il nome del servizio è obbligatorio',
        'name_string' => 'Il nome del servizio deve essere una stringa',
        'name_unique' => 'Questo nome del servizio esiste già',
        'category_required' => 'La categoria è obbligatoria',
        'category_exists' => 'La categoria selezionata non è valida',
        'duration_required' => 'La durata è obbligatoria',
        'duration_format' => 'Inserisci un formato orario valido',
        'price_required' => 'Il prezzo è obbligatorio',
        'price_decimal' => 'Inserisci un prezzo valido',
        'availability_required' => 'La disponibilità è obbligatoria',
        'availability_boolean' => 'La disponibilità deve essere un valore booleano',
    ],
];
