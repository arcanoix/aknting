<?php

return [

    Modules\Pos\Models\Order::class => [
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
                'key'   => 'customer',
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

];
