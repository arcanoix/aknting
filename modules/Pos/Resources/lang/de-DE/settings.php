<?php

return [

    'bank_card_payment_method' => [
        'name'        => 'Bankkarte',
        'description' => 'Zahlung mit einer Bankkarte. Diese Zahlungsmethode wird in der PoS App verwendet.',
    ],

    'categories' => [
        'pos_sale'   => 'PoS - Einnahmen',
        'pos_change' => 'Pos - Ausgaben',
    ],

    'accounts' => [
        'cash' => 'Bar',
        'bank' => 'Bank',
    ],

    'order' => [
        'order_numbering' => 'Bestellnummer',
        'prefix'          => 'Rechnungsprefix',
        'digit'           => 'Nachkommastellen',
        'next'            => 'Nächste Nummer',
    ],

    'cash' => [
        'payments_by_cash' => 'Zahlungen per Bargeld',
    ],

    'card' => [
        'payments_by_card' => 'Zahlungen per Karte',
    ],

    'general' => [
        'other'              => 'Andere',
        'guest_customer'     => 'Gast Kunde',
        'sale_category'      => 'Kategorie - Einnahmen',
        'change_category'    => 'Kategorie - Ausgaben',
        'account'            => 'Konto',
        'printer_paper_size' => 'Druckerpapiergröße',
    ],

    'email' => [
        'templates' => [
            'order_receipt_customer' => 'Vorlage für Beleg (an Kunden gesendet)',
        ],
    ],

];
