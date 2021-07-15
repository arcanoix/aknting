<?php

return [
    // Documents
    \Modules\Pos\Models\Order::TYPE => [
        'alias'            => 'pos',
        'group'            => '',
        'route'            => [
            'prefix'    => 'orders',
            'parameter' => 'order',
        ],
        'permission'       => [
            'prefix' => 'orders',
        ],
        'translation'      => [
            'prefix'         => 'orders',
            'document_title' => 'pos::general.orders',
        ],
        'category_type'    => 'income',
        'transaction_type' => 'income',
        'contact_type'     => 'customer',
        'hide'             => [],
        'class'            => [],
    ],
];
