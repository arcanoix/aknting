<?php

return [

    'bank_card_payment_method' => [
        'name'        => 'Bank Card',
        'description' => 'Payment by a bank card. This payment method is used in the PoS app.',
    ],

    'categories' => [
        'pos_sale'   => 'PoS Sale',
        'pos_change' => 'PoS Change',
    ],

    'accounts' => [
        'cash' => 'Cash',
        'bank' => 'Bank',
    ],

    'order' => [
        'order_numbering' => 'Order Numbering',
        'prefix'          => 'Number Prefix',
        'digit'           => 'Number Digit',
        'next'            => 'Next Number',
    ],

    'cash' => [
        'payments_by_cash' => 'Payments By Cash',
    ],

    'card' => [
        'payments_by_card' => 'Payments By Card',
    ],

    'general' => [
        'other'                => 'Other',
        'guest_customer'       => 'Guest Customer',
        'sale_category'        => 'Sale Category',
        'change_category'      => 'Change Category',
        'account'              => 'Account',
        'printer_paper_size'   => 'Printer Paper Size',
        'use_paper_cutter'     => 'Use Paper Cutter',
        'show_logo_in_receipt' => 'Show Company Logo In Receipt',
        'use_barcode_scanner'  => 'Use Barcode Scanner',
    ],

    'email' => [
        'templates' => [
            'order_receipt_customer' => 'Order Receipt Template (sent to customer)',
        ],
    ],

];
