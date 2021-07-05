<?php

return [

    'debit_note_number'           => 'Debit Note Number',
    'document_number'             => 'Debit Note Number',
    'debit_note_date'             => 'Debit Note Date',
    'issued_at'                   => 'Debit Note Date',
    'total_price'                 => 'Total Price',
    'issue_date'                  => 'Issue Date',
    'related_bill_number'         => 'Bill Number',
    'debit_note_to'               => 'Debit Note To',
    'contact_info'                => 'Debit Note To',

    'quantity'                    => 'Quantity',
    'price'                       => 'Price',
    'sub_total'                   => 'Subtotal',
    'discount'                    => 'Discount',
    'item_discount'               => 'Line Discount',
    'tax_total'                   => 'Tax Total',
    'total'                       => 'Total',

    'item_name'                   => 'Item Name|Item Names',

    'show_discount'               => ':discount% Discount',
    'add_discount'                => 'Add Discount',
    'discount_desc'               => 'of subtotal',

    'refund_from_vendor'          => 'Refund from a vendor',
    'received_refund_from_vendor' => 'Received :amount as a refund from :vendor',

    'histories'                   => 'Histories',
    'type'                        => 'Type',
    'refund'                      => 'Refund',
    'mark_sent'                   => 'Mark Sent',
    'receive_refund'              => 'Receive Refund',
    'mark_viewed'                 => 'Mark Viewed',
    'mark_cancelled'              => 'Mark Cancelled',
    'download_pdf'                => 'Download PDF',
    'send_mail'                   => 'Send Email',
    'all_debit_notes'             => 'Login to view all debit notes',
    'create_debit_note'           => 'Create Debit Note',
    'send_debit_note'             => 'Send Debit Note',
    'timeline_sent_title'         => 'Send Debit Note',

    'statuses' => [
        'draft'     => 'Draft',
        'sent'      => 'Sent',
        'viewed'    => 'Viewed',
        'cancelled' => 'Cancelled',
    ],

    'messages' => [
        'email_sent'          => 'Debit Note email has been sent!',
        'marked_viewed'       => 'Debit Note marked as viewed!',
        'refund_was_received' => 'Refund Was Received!',
        'email_required'      => 'No email address for this customer!',
        'draft'               => 'This is a <b>DRAFT</b> debit note and will be reflected to charts after it gets sent.',

        'status' => [
            'created' => 'Created on :date',
            'viewed'  => 'Viewed',
            'send'    => [
                'draft' => 'Not sent',
                'sent'  => 'Sent on :date',
            ],
        ],
    ],

];
