<?php

return [
    'show_custom_fields' => true,
    'custom_fields' => [
        'last_name' => [
            'type' => 'text',
            'label' => 'Lastname',
            'placeholder' => 'lastname',
            'required' => true,
            'rules' => 'required|string|max:255',
        ],
    ]
];
