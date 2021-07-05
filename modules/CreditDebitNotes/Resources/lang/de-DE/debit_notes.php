<?php

return [

    'debit_note_number'           => 'Lastschrift Nummer',
    'document_number'             => 'Lastschrift Nummer',
    'debit_note_date'             => 'Lastschrift Datum',
    'issued_at'                   => 'Lastschrift Datum',
    'total_price'                 => 'Gesamtpreis',
    'issue_date'                  => 'Rechnungsdatum',
    'related_bill_number'         => 'Rechnungsnummer',
    'debit_note_to'               => 'Lastschrift Datum',
    'contact_info'                => 'Lastschrift Datum',

    'quantity'                    => 'Menge',
    'price'                       => 'Preis',
    'sub_total'                   => 'Zwischensumme',
    'discount'                    => 'Rabatt',
    'item_discount'               => 'Positions-Rabatt',
    'tax_total'                   => 'Steuern Total',
    'total'                       => 'Total',

    'item_name'                   => 'Artikelname|Artikelnamen',

    'show_discount'               => ':discount% Rabatt',
    'add_discount'                => 'Rabatt hinzufügen',
    'discount_desc'               => 'von Zwischensumme',

    'refund_from_vendor'          => 'Lastschrift von einem Kreditor',
    'received_refund_from_vendor' => ':amount als Lastschrift von :vendor erhalten',

    'histories'                   => 'Historie',
    'type'                        => 'Typ',
    'refund'                      => 'Lastschrift',
    'mark_sent'                   => 'Als gesendet markieren',
    'receive_refund'              => 'Lastschrift erhalten',
    'mark_viewed'                 => 'Als gelesen markieren',
    'mark_cancelled'              => 'Als storniert markieren',
    'download_pdf'                => 'Als PDF herunterladen',
    'send_mail'                   => 'E-Mail senden',
    'all_debit_notes'             => 'Melden Sie sich an, um alle Lastschriften anzuzeigen',
    'create_debit_note'           => 'Lastschrift erstellen',
    'send_debit_note'             => 'Lastschrift senden',
    'timeline_sent_title'         => 'Lastschrift senden',

    'statuses' => [
        'draft'     => 'Entwurf',
        'sent'      => 'Versandt',
        'viewed'    => 'Gelesen',
        'cancelled' => 'Storniert',
    ],

    'messages' => [
        'email_sent'          => 'Lastschrift wurde per E-Mail versendet!',
        'marked_viewed'       => 'Lastschrift als <strong>gelesen</strong> markiert!',
        'refund_was_received' => 'Lastschrift wurde erhalten!',
        'email_required'      => 'Es existiert keine E-Mailadresse zu diesem Kreditor!',
        'draft'               => 'Dies ist eine <b>Vorschau</b>-Lastschrift und wird nach dem Versand in den Charts ersichtlich.',

        'status' => [
            'created' => 'Erstellt am :date',
            'viewed'  => 'Gelesen',
            'send'    => [
                'draft' => 'Noch nicht versandt',
                'sent'  => 'Gesendet am :date',
            ],
        ],
    ],

];
