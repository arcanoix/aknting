<?php

return [


    'pagesize'=>[
        'a4Horizontal'=>'A4 Horizontal',
        'a4Vertical'=>'A4 Vertikal',
        'a5Horizontal'=>'A5 Horizontal',
        'a5Vertical'=>'A4 Vertikal',
        'Letter'=>'Letter',
        'Ledger'=>'Ledger',
        'Legal'=>'Legal',
    ],

    'type'=>[
        'invoice'=>'Rechnungen - Einnahmen',
        'bill'=>'Rechnung - Ausgaben',
    ],

    'category'=>[
        'company_info'=>"Firmendetails",
        'customer_info'=>"Kundendetails",
        'invoice_info'=>"Rechnungsdetails",
        'invoiceitem_info'=>"Rechnungspositionen",
        'invoicetotal_info'=>"Zusammenstellung - Total",
    ],
    
    'items'=>[
        'company_name'         =>'Name',
        'company_address'      =>'Adresse',
        'company_tax_number'   =>'Steuernummer',
        'company_phone'        =>'Telefon',
        'company_email'        =>'E-Mail',

        
        'customer_name'         =>'Kunden.Name',
        'customer_email'        =>'Kunden.E-Mail',
        'customer_tax_number'   =>'Kunden.Steuernummer',
        'customer_phone'        =>'Kunden.Telefon ',
        'customer_website'      =>'Kunden.Webseite',
        'customer_address'      =>'Kunden.Adresse',
        'customer_reference'    =>'Kunden.Referenz',

        'invoice_number'        =>'Rechnungsnummer',
        'order_number'          =>'Bestellnummer',

        'invoiced_at'           =>'Rechnungsdatum (Datum und Zeit)',
        'invoiced_at_date'      =>'Rechnungsdatum',
        'invoiced_at_hour'      =>'Rechnungsdatum (Zeit)',

        'due_at'                =>'Fälligkeitsdatum (Datum und Zeit)',
        'due_at_date'           =>'Fälligkeitsdatum (Datum)',
        'due_at_hour'           =>'Fälligkeitsdatum (Zeit)',
        'notes'                 =>'Notizen',

        'item_name'             =>'Position.Name',
        'item_sku'              =>'Position.Nummer',
        'item_quantity'         =>'Position.Anzahl',
        'item_price'            =>'Position.Preis',
        'item_total'            =>'Total',
        'item_tax'              =>'Position.Steuern',

        'total_sub_total'           =>'Zwischensumme',
        'total_tax'                 =>'Steuern',
        'total_discount'            =>'Rabatt',
        'total_total'               =>'Gesamt',
        'total_total_word'          =>'Gesamt Text',
        'total_sub_total_with_type' =>'Zwischensumme mit Text',
        'total_tax_with_type'       =>'Steuern mit Text',
        'total_total_with_type'     =>'Gesamt mit Text',
        'total_discount_with_type'  =>'Rabatt mit Text',
        'tax_only_name'       =>'Steuer - Name',
        'paid'                      =>'Bezahlt',
        'paid_only_name'            =>'Bezahlt (Nur Text)',
        'paid_with_type'            =>'Bezahlt (mit Text)',
        'amount'                    =>'Betrag',
        'amount_only_name'          =>'Betrag (Nur Text)',
        'amount_with_type'          =>'Betrag (mit Text)',
    ]

];
