<?php

return [
    // Documents
    \Modules\CreditDebitNotes\Models\CreditNote::TYPE => [
        'alias'            => 'credit-debit-notes',
        'group'            => '',
        'route'            => [
            'prefix'    => 'credit-notes',
            'parameter' => 'credit_note',
        ],
        'permission'       => [
            'prefix' => 'credit-notes',
        ],
        'translation'      => [
            'prefix'             => 'credit_notes',
            'advanced_accordion' => 'credit-debit-notes::general.category',
        ],
        'setting'          => [
            'prefix' => 'credit_note',
        ],
        'category_type'    => 'income',
        'transaction_type' => 'income',
        'contact_type'     => 'customer',
        'hide'             => [],
        'class'            => [],
    ],

    \Modules\CreditDebitNotes\Models\DebitNote::TYPE => [
        'alias'            => 'credit-debit-notes',
        'group'            => '',
        'route'            => [
            'prefix'    => 'debit-notes',
            'parameter' => 'debit_note',
        ],
        'permission'       => [
            'prefix' => 'debit-notes',
        ],
        'translation'      => [
            'prefix'             => 'debit_notes',
            'advanced_accordion' => 'credit-debit-notes::general.category',
        ],
        'setting'          => [
            'prefix' => 'debit_note',
        ],
        'category_type'    => 'expense',
        'transaction_type' => 'expense',
        'contact_type'     => 'vendor',
        'hide'             => [],
        'class'            => [],
    ],
];
