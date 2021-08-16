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
        'pos' => [
            'pos_order' => [
                'number_prefix' => env('SETTING_FALLBACK_POS_ORDER_NUMBER_PREFIX', 'ORD-'),
                'number_digit'  => env('SETTING_FALLBACK_POS_ORDER_NUMBER_DIGIT', '5'),
                'number_next'   => env('SETTING_FALLBACK_POS_ORDER_NUMBER_NEXT', '1'),
            ],
            'general'   => [
                'cash_account_id'      => env('SETTING_FALLBACK_POS_CASH_ACCOUNT_ID', null),
                'cash_payment_method'  => env('SETTING_FALLBACK_POS_CASH_PAYMENT_METHOD', 'offline-payments.cash.1'),
                'card_account_id'      => env('SETTING_FALLBACK_POS_CARD_ACCOUNT_ID', null),
                'card_payment_method'  => env('SETTING_FALLBACK_POS_CARD_PAYMENT_METHOD', 'offline-payments.bank_card.pos'),
                'guest_customer_id'    => env('SETTING_FALLBACK_POS_GENERAL_GUEST_CUSTOMER_ID', null),
                'sale_category_id'     => env('SETTING_FALLBACK_POS_GENERAL_SALE_CATEGORY_ID', null),
                'change_category_id'   => env('SETTING_FALLBACK_POS_GENERAL_CHANGE_CATEGORY_ID', null),
                'printer_paper_size'   => env('SETTING_FALLBACK_POS_GENERAL_PRINTER_PAPER_SIZE', 80),
                'use_paper_cutter'     => env('SETTING_FALLBACK_POS_GENERAL_USE_PAPER_CUTTER', true),
                'show_logo_in_receipt' => env('SETTING_FALLBACK_POS_GENERAL_SHOW_LOGO_IN_RECEIPT', true),
                'use_barcode_scanner'  => env('SETTING_FALLBACK_POS_GENERAL_USE_BARCODE_SCANNER', true),
            ],
        ],
    ],

];
