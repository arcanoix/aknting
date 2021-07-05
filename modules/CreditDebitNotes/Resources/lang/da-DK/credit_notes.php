<?php

return [

    'credit_note_number'      => 'Kreditnotanummer',
    'document_number'         => 'Kreditnotanummer',
    'credit_note_date'        => 'Dato for kreditnota',
    'issued_at'               => 'Dato for kreditnota',
    'total_price'             => 'Total pris',
    'issue_date'              => 'Udstedelsesdato',
    'related_invoice_number'  => 'Fakturanummer',
    'bill_to'                 => 'Regning til',

    'quantity'                => 'Antal',
    'price'                   => 'Pris',
    'sub_total'               => 'Subtotal',
    'discount'                => 'Rabat',
    'item_discount'           => 'Linjerabat',
    'tax_total'               => 'Moms i alt',
    'total'                   => 'Total',

    'item_name'               => 'Varenavn|Varenavne',

    'credit_customer_account' => 'Krediter medlemskonto',
    'show_discount'           => ':discount% rabat',
    'add_discount'            => 'Tilføj rabat',
    'discount_desc'           => 'af subtotal',

    'customer_credited_with'  => ':customer krediteret med :amount',
    'credit_cancelled'        => ':amount kredit annulleret',
    'refunded_customer_with'  => 'Tilbagebetalt :customer :amount',
    'refund_to_customer'      => 'Tilbagebetal til et medlem',

    'histories'               => 'Historik',
    'type'                    => 'Type',
    'credit'                  => 'Kredit',
    'refund'                  => 'Tilbagebetaling',
    'make_refund'             => 'Lav tilbagebetaling',
    'mark_sent'               => 'Marker som sendt',
    'mark_viewed'             => 'Marker som set',
    'mark_cancelled'          => 'Marker som annulleret',
    'download_pdf'            => 'Download PDF',
    'send_mail'               => 'Send e-mail',
    'all_credit_notes'        => 'Log ind for at se alle kreditnotaer',
    'create_credit_note'      => 'Opret kreditnota',
    'send_credit_note'        => 'Send kreditnota',
    'timeline_sent_title'     => 'Send kreditnota',

    'statuses' => [
        'draft'     => 'Udkast',
        'sent'      => 'Sendt',
        'viewed'    => 'Set',
        'approved'  => 'Godkendt',
        'partial'   => 'Delvis',
        'cancelled' => 'Annulleret',
    ],

    'messages' => [
        'email_sent'       => 'Kreditnota email er blevet sendt!',
        'marked_sent'      => 'Kreditnota markeret som sendt!',
        'marked_viewed'    => 'Kreditnota markeret som set!',
        'marked_cancelled' => 'Kreditnota markeret som annulleret!',
        'refund_was_made'  => 'Tilbagebetaling er gennemført!',
        'email_required'   => 'Ingen e-mailadresse for denne kunde!',
        'draft'            => 'Dette er et <b>UDKAST</b> til en kreditnota, og vil blive afspejlet i diagrammer, når den bliver sendt.',

        'status' => [
            'created' => 'Oprettet den :date',
            'viewed'  => 'Set',
            'send'    => [
                'draft' => 'Ikke sendt',
                'sent'  => 'Sendt den :date',
            ],
        ],
    ],

];
