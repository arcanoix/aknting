<?php

return [

    Modules\CreditDebitNotes\Models\CreditNote::class => [
        'columns' => [
            'id',
            'document_number' => ['searchable' => true],
            'status',
            'issued_at'       => ['date' => true],
            'amount',
            'currency_code'   => [
                'route' => 'currencies.index'
            ],
            'contact_id'      => [
                'route' => 'customers.index'
            ],
            'contact_name'    => ['searchable' => true],
            'contact_email'   => ['searchable' => true],
            'contact_tax_number',
            'contact_phone'   => ['searchable' => true],
            'contact_address' => ['searchable' => true],
            'category_id'     => [
                'route' => 'categories.index'
            ],
        ],
    ],

    Modules\CreditDebitNotes\Models\DebitNote::class => [
        'columns' => [
            'id',
            'document_number' => ['searchable' => true],
            'status',
            'issued_at'       => ['date' => true],
            'amount',
            'currency_code'   => [
                'route' => 'currencies.index'
            ],
            'contact_id'      => [
                'route' => 'vendors.index'
            ],
            'contact_name'    => ['searchable' => true],
            'contact_email'   => ['searchable' => true],
            'contact_tax_number',
            'contact_phone'   => ['searchable' => true],
            'contact_address' => ['searchable' => true],
            'category_id'     => [
                'route' => 'categories.index'
            ],
        ],
    ],

    Modules\CreditDebitNotes\Models\Portal\CreditNote::class => [
        'columns' => [
            'id',
            'document_number' => ['searchable' => true],
            'status',
            'issued_at'       => ['date' => true],
            'amount',
            'currency_code'   => [
                'route' => 'currencies.index'
            ],
        ],
    ],

];
