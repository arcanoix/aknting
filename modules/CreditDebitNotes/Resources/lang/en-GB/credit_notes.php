<?php

return [

    'credit_note_number'      => 'Credit Note Number',
    'document_number'         => 'Credit Note Number',
    'credit_note_date'        => 'Credit Note Date',
    'issued_at'               => 'Credit Note Date',
    'total_price'             => 'Total Price',
    'issue_date'              => 'Issue Date',
    'related_invoice_number'  => 'Invoice Number',
    'bill_to'                 => 'Bill To',

    'quantity'                => 'Quantity',
    'price'                   => 'Price',
    'sub_total'               => 'Subtotal',
    'discount'                => 'Discount',
    'item_discount'           => 'Line Discount',
    'tax_total'               => 'Tax Total',
    'total'                   => 'Total',

    'item_name'               => 'Item Name|Item Names',

    'credit_customer_account' => 'Credit Customer Account',
    'show_discount'           => ':discount% Discount',
    'add_discount'            => 'Add Discount',
    'discount_desc'           => 'of subtotal',

    'customer_credited_with'  => ':customer credited with :amount',
    'credit_cancelled'        => ':amount credit cancelled',
    'refunded_customer_with'  => 'Refunded :customer with :amount',
    'refund_to_customer'      => 'Refund to a customer',

    'histories'               => 'Histories',
    'type'                    => 'Type',
    'credit'                  => 'Credit',
    'refund'                  => 'Refund',
    'make_refund'             => 'Make Refund',
    'mark_sent'               => 'Mark Sent',
    'mark_viewed'             => 'Mark Viewed',
    'mark_cancelled'          => 'Mark Cancelled',
    'download_pdf'            => 'Download PDF',
    'send_mail'               => 'Send Email',
    'all_credit_notes'        => 'Login to view all credit notes',
    'create_credit_note'      => 'Create Credit Note',
    'send_credit_note'        => 'Send Credit Note',
    'timeline_sent_title'     => 'Send Credit Note',

    'statuses' => [
        'draft'     => 'Draft',
        'sent'      => 'Sent',
        'viewed'    => 'Viewed',
        'approved'  => 'Approved',
        'partial'   => 'Partial',
        'cancelled' => 'Cancelled',
    ],

    'messages' => [
        'email_sent'       => 'Credit Note email has been sent!',
        'marked_sent'      => 'Credit Note marked as sent!',
        'marked_viewed'    => 'Credit Note marked as viewed!',
        'marked_cancelled' => 'Credit Note marked as cancelled!',
        'refund_was_made'  => 'Refund Was Made!',
        'email_required'   => 'No email address for this customer!',
        'draft'            => 'This is a <b>DRAFT</b> credit note and will be reflected to charts after it gets sent.',

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
