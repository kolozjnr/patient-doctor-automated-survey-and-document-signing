<?php

return [
    'required' => ':attribute is verplicht.',
    'string' => ':attribute moet een tekst zijn.',
    'min' => [
        'string' => ':attribute moet minimaal :min tekens bevatten.',
        'array' => ':attribute moet minimaal :min items bevatten.',
    ],
    'email' => 'Voer een geldig e-mailadres in.',
    'unique' => 'Dit :attribute bestaat al. Kies een ander.',
    'date' => 'Voer een geldige datum in voor :attribute.',
    'in' => 'Het geselecteerde :attribute is ongeldig.',
    'exists' => 'Het geselecteerde :attribute bestaat niet.',
    'array' => ':attribute moet een lijst zijn.',

    'custom' => [
        'patient_id' => [
            'required' => 'Patiënt-ID is verplicht.',
            'unique' => 'Dit patiënt-ID bestaat al. Gebruik een ander.',
        ],
        'first_name' => [
            'required' => 'Voornaam is verplicht.',
        ],
        'last_name' => [
            'required' => 'Achternaam is verplicht.',
        ],
        'email' => [
            'required' => 'E-mailadres is verplicht.',
            'email' => 'Voer een geldig e-mailadres in.',
            'unique' => 'Dit e-mailadres is al geregistreerd.',
        ],
        'date_of_birth' => [
            'required' => 'Geboortedatum is verplicht.',
            'date' => 'Voer een geldige geboortedatum in.',
        ],
        'opt_for_daily' => [
            'required' => 'Selecteer een voorkeur voor dagelijkse meldingen.',
        ],
        'user_type' => [
            'required' => 'Gebruikerstype is verplicht.',
            'in' => 'Ongeldig gebruikerstype geselecteerd.',
        ],
        'labels' => [
            'required' => 'Selecteer minimaal één label.',
            'min' => 'Selecteer minimaal één label.',
        ],
        'departments' => [
            'required' => 'Selecteer minimaal één afdeling.',
            'min' => 'Selecteer minimaal één afdeling.',
        ],
    ],

    'attributes' => [
        'patient_id' => 'Patiënt-ID',
        'first_name' => 'Voornaam',
        'last_name' => 'Achternaam',
        'email' => 'E-mailadres',
        'date_of_birth' => 'Geboortedatum',
        'opt_for_daily' => 'Dagelijkse meldingsvoorkeur',
        'user_type' => 'Gebruikerstype',
        'labels' => 'Labels',
        'departments' => 'Afdelingen',
    ],
];