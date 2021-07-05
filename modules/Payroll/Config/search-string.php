<?php

return [

    Modules\Payroll\Models\PayCalendar\PayCalendar::class => [
        'columns' => [
            'id',
            'name' => ['searchable' => true],
            'type' => [
                'route' => 'payroll.pay-calendars.types',
            ],
        ],
    ],

    Modules\Payroll\Models\RunPayroll\RunPayroll::class => [
        'columns' => [
            'id',
            'name'         => ['searchable' => true],
            'status'       => [
                'route' => 'payroll.run-payrolls.statuses',
            ],
            'from_date'    => [
                'date' => true,
            ],
            'to_date'      => [
                'date' => true,
            ],
            'payment_date' => [
                'date' => true,
            ],
            'amount',
        ],
    ],

];
