<?php

return [

    'credit_note_number'      => 'Gutschrift Nummer',
    'document_number'         => 'Gutschrift Nummer',
    'credit_note_date'        => 'Gutschrift Datum',
    'issued_at'               => 'Gutschrift Datum',
    'total_price'             => 'Gesamtpreis',
    'issue_date'              => 'Rechnungsdatum',
    'related_invoice_number'  => 'Rechnungsnummer',
    'bill_to'                 => 'Rechnung an',

    'quantity'                => 'Menge',
    'price'                   => 'Preis',
    'sub_total'               => 'Zwischensumme',
    'discount'                => 'Rabatt',
    'item_discount'           => 'Positions-Rabatt',
    'tax_total'               => 'Steuern Total',
    'total'                   => 'Total',

    'item_name'               => 'Artikelname|Artikelnamen',

    'credit_customer_account' => 'Kundenkonto',
    'show_discount'           => ':discount% Rabatt',
    'add_discount'            => 'Rabatt hinzufügen',
    'discount_desc'           => 'von Zwischensumme',

    'customer_credited_with'  => 'Betrag von :amount an :customer gutgeschrieben',
    'credit_cancelled'        => 'Gutschrift von :amount storniert',
    'refunded_customer_with'  => 'Gutschrift von :amount für :customer',
    'refund_to_customer'      => 'Rückerstattung an Kunden',

    'histories'               => 'Historie',
    'type'                    => 'Typ',
    'credit'                  => 'Gutschrift',
    'refund'                  => 'Rückerstattung',
    'make_refund'             => 'Gutschrift erstellen',
    'mark_sent'               => 'Als gesendet markieren',
    'mark_viewed'             => 'Als gelesen markieren',
    'mark_cancelled'          => 'Als storniert markieren',
    'download_pdf'            => 'Als PDF herunterladen',
    'send_mail'               => 'E-Mail senden',
    'all_credit_notes'        => 'Melden Sie sich an, um alle Gutschriften anzuzeigen',
    'create_credit_note'      => 'Gutschrift erstellen',
    'send_credit_note'        => 'Gutschrift senden',
    'timeline_sent_title'     => 'Gutschrift senden',

    'statuses' => [
        'draft'     => 'Entwurf',
        'sent'      => 'Versandt',
        'viewed'    => 'Gelesen',
        'approved'  => 'Freigegeben',
        'partial'   => 'Teilweise',
        'cancelled' => 'Storniert',
    ],

    'messages' => [
        'email_sent'       => 'Gutschrift wurde per E-Mail versendet!',
        'marked_sent'      => 'Gutschrift als <strong>gesendet</strong> markiert!',
        'marked_viewed'    => 'Gutschrift als <strong>gelesen</strong> markiert!',
        'marked_cancelled' => 'Gutschrift als <strong>storniert</strong> markiert!',
        'refund_was_made'  => 'Gutschrift wurde erstellt!',
        'email_required'   => 'Es existiert keine E-Mailadresse zu diesem Kunden!',
        'draft'            => 'Dies ist eine <b>Vorschau</b>-Gutschrift und wird nach dem Versand in den Charts ersichtlich.',

        'status' => [
            'created' => 'Erstellt am :date',
            'viewed'  => 'Gelesen',
            'send'    => [
                'draft' => 'Nicht gesendet',
                'sent'  => 'Gesendet am :date',
            ],
        ],
    ],

];
