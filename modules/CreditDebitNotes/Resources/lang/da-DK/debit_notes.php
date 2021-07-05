<?php

return [

    'debit_note_number'           => 'Debetnotanummer',
    'document_number'             => 'Debetnotanummer',
    'debit_note_date'             => 'Dato for debetnota',
    'issued_at'                   => 'Dato for debetnota',
    'total_price'                 => 'Total pris',
    'issue_date'                  => 'Udstedelsesdato',
    'related_bill_number'         => 'Regningsnummer',
    'debit_note_to'               => 'Debetnote til',
    'contact_info'                => 'Debetnote til',

    'quantity'                    => 'Antal',
    'price'                       => 'Pris',
    'sub_total'                   => 'Subtotal',
    'discount'                    => 'Rabat',
    'item_discount'               => 'Linjerabat',
    'tax_total'                   => 'Moms i alt',
    'total'                       => 'Total',

    'item_name'                   => 'Varenavn|Varenavne',

    'show_discount'               => ':discount% rabat',
    'add_discount'                => 'Tilføj rabat',
    'discount_desc'               => 'af subtotal',

    'refund_from_vendor'          => 'Tilbagebetaling fra en leverandør',
    'received_refund_from_vendor' => 'Modtaget :amount som tilbagebetaling fra :vendor',

    'histories'                   => 'Historik',
    'type'                        => 'Type',
    'refund'                      => 'Tilbagebetaling',
    'mark_sent'                   => 'Marker som sendt',
    'receive_refund'              => 'Modtag tilbagebetaling',
    'mark_viewed'                 => 'Marker som set',
    'mark_cancelled'              => 'Marker som annulleret',
    'download_pdf'                => 'Download PDF',
    'send_mail'                   => 'Send e-mail',
    'all_debit_notes'             => 'Log ind for at se alle debetnotaer',
    'create_debit_note'           => 'Opret debetnota',
    'send_debit_note'             => 'Send debetnota',
    'timeline_sent_title'         => 'Send debetnota',

    'statuses' => [
        'draft'     => 'Udkast',
        'sent'      => 'Sendt',
        'viewed'    => 'Set',
        'cancelled' => 'Annulleret',
    ],

    'messages' => [
        'email_sent'          => 'Debetnota e-mail er blevet sendt!',
        'marked_viewed'       => 'Debetnota markeret som set!',
        'refund_was_received' => 'Tilbagebetaling blev modtaget!',
        'email_required'      => 'Ingen e-mailadresse for denne kunde!',
        'draft'               => 'Dette er et <b>UDKAST</b> til en Debetnota, og vil blive afspejlet i diagrammer, når den bliver sendt.',

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
