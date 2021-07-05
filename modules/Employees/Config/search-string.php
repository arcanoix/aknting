<?php

return [

    Modules\Employees\Models\Position::class => [
        'columns' => [
            'id',
            'name'    => ['searchable' => true],
            'enabled' => ['boolean' => true],
        ],
    ],

    Modules\Employees\Models\Employee::class => [
        'columns' => [
            'id',
            'contacts.name'       => ['searchable' => true],
            'contacts.email'      => ['searchable' => true],
            'contacts.tax_number' => ['searchable' => true],
            'contacts.phone'      => ['searchable' => true],
            'contacts.address'    => ['searchable' => true],
//            'contacts.enabled'    => ['boolean' => true],
            'bank_account_number' => ['searchable' => true],
            'hired_at'            => ['date' => true],
            'birth_day'           => ['date' => true],
//            'gender'              => [
//                'values' => [
//                    'employees::employees.male',
//                    'employees::employees.female',
//                    'employees::employees.other',
//                ]
//            ],
            'amount',
        ],
    ],

];
