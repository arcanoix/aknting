<?php

return [

    'credit_note_number'      => 'شماره یادداشت اعتباری',
    'credit_note_date'        => 'تاریخ یادداشت اعتباری',
    'total_price'             => 'قیمت کل',
    'issue_date'              => 'تاریخ صدور',
    'invoice_number'          => 'شماره فاکتور',
    'bill_to'                 => 'صورتحساب برای',

    'quantity'                => 'تعداد',
    'price'                   => 'قيمت',
    'sub_total'               => 'جمع کل',
    'discount'                => 'تخفیف',
    'item_discount'           => 'تخفیف خط',
    'tax_total'               => 'مجموع مالیات',
    'total'                   => 'مجموع',

    'item_name'               => 'نام آیتم | نام آیتم ها',

    'credit_customer_account' => 'اعتبار حساب مشتری',
    'show_discount'           => ':discount% تخفیف',
    'add_discount'            => 'افزودن تخفیف',
    'discount_desc'           => 'از جمع کل',

    'customer_credited_with'  => ':customer اعتبار داده شده با :amount',
    'credit_cancelled'        => ':amount اعتبار لغو شده',
    'refunded_customer_with'  => 'مسترد شده :customer با :amount',
    'refund_to_customer'      => 'بازپرداخت به مشتری',

    'histories'               => 'تاریخچه',
    'type'                    => 'نوع',
    'credit'                  => 'اعتبار',
    'refund'                  => 'باز پرداخت',
    'make_refund'             => 'تغییر وضعیت به بازپرداخت شده',
    'mark_sent'               => 'تغییر وضعیت به ارسال شده',
    'mark_viewed'             => 'تغییر وضعیت به مشاهده شده',
    'mark_cancelled'          => 'تغییر وضعیت به لغو شده',
    'download_pdf'            => 'دانلود PDF',
    'send_mail'               => 'ارسال ایمیل',
    'all_credit_notes'        => 'برای مشاهده همه یادداشتهای بدهی وارد شوید',
    'create_credit_note'      => 'ایجاد یادداشت بدهی',
    'send_credit_note'        => 'ارسال یادداشت بدهی',

    'statuses' => [
        'draft'     => 'پیش‌نویس',
        'sent'      => 'ارسال',
        'viewed'    => 'مشاهده شده',
        'approved'  => 'تایید شده',
        'partial'   => 'جزئی',
        'cancelled' => 'لغو شده',
    ],

    'messages' => [
        'email_sent'       => 'ایمیل یادداشت اعتباری ارسال شد!',
        'marked_sent'      => 'یادداشت اعتباری علامت گذاری شده ارسال شد!',
        'marked_viewed'    => 'یادداشت اعتباری علامت گذاری شده مشاهده شد!',
        'marked_cancelled' => 'یادداشت اعتباری علامت گذاری شده لغو شد!',
        'refund_was_made'  => 'بازپرداخت انجام شد!',
        'email_required'   => 'برای مشتری ایمیل ثبت نشده است!',
        'draft'            => 'این یک <b>پیشنویس</b> از یادداشت اعتباری است و پس از ارسال بر روی نمودار اعمال می شود.',

        'status' => [
            'created' => 'ایجاد شده در :date',
            'viewed'  => 'مشاهده شده',
            'send'    => [
                'draft' => 'ارسال نشده',
                'sent'  => 'ارسال شده در :date',
            ],
        ],
    ],

];
