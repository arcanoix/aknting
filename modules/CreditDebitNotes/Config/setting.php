<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fallback
    |--------------------------------------------------------------------------
    |
    | Define fallback settings to be used in case the default is null
    |
    | Sample:
    |   "currency" => "USD",
    |
    */
    'fallback' => [
        'credit-debit-notes' => [
            'credit_note' => [
                'number_prefix' => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_CREDIT_NOTE_NUMBER_PREFIX', 'CN-'),
                'number_digit'  => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_CREDIT_NOTE_NUMBER_DIGIT', '5'),
                'number_next'   => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_CREDIT_NOTE_NUMBER_NEXT', '1'),
                'item_name'     => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_CREDIT_NOTE_ITEM_NAME', 'credit-debit-notes::settings.credit_note.item'),
                'price_name'    => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_CREDIT_NOTE_PRICE_NAME', 'credit-debit-notes::settings.credit_note.price'),
                'quantity_name' => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_CREDIT_NOTE_QUANTITY_NAME', 'credit-debit-notes::settings.credit_note.quantity'),
                'template'      => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_CREDIT_NOTE_TEMPLATE', 'default'),
                'color'         => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_CREDIT_NOTE_COLOR', '#55588b'),
            ],
            'debit_note'  => [
                'number_prefix' => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_DEBIT_NOTE_NUMBER_PREFIX', 'DN-'),
                'number_digit'  => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_DEBIT_NOTE_NUMBER_DIGIT', '5'),
                'number_next'   => env('SETTING_FALLBACK_CREDIT_DEBIT_NOTES_DEBIT_NOTE_NUMBER_NEXT', '1'),
            ],
        ],
    ],

];
