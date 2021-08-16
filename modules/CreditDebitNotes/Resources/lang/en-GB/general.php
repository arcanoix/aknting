<?php

return [

    'name'        => 'Credit/Debit Notes',
    'description' => 'Create credit or debit notes',

    'credit_notes' => 'Credit Note|Credit Notes',
    'debit_notes'  => 'Debit Note|Debit Notes',

    'vendors' => 'Vendor|Vendors',

    'category' => 'Category',

    'empty' => [
        'credit_notes' => 'Credit notes are typically used when there has been an error in an already-issued invoice, such as an incorrect amount, or damaged goods, or when a customer wishes to change their original order.',
        'debit_notes'  => 'Debit notes are typically issued from a customer to their seller to indicate or request a return of funds due to incorrect or damaged goods received, purchase cancellation, or other specified circumstances.',
    ],

];
