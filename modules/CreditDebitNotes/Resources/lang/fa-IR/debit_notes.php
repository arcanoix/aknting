<?php

return [

    'debit_note_number'           => 'شماره یادداشت اعتباری',
    'debit_note_date'             => 'تاریخ یادداشت اعتباری',
    'total_price'                 => 'قیمت کل',
    'issue_date'                  => 'تاریخ صدور',
    'bill_number'                 => 'شماره صورتحساب',
    'debit_note_to'               => 'یادداشت بدهی تا',

    'quantity'                    => 'تعداد',
    'price'                       => 'قيمت',
    'sub_total'                   => 'جمع کل',
    'discount'                    => 'تخفیف',
    'item_discount'               => 'تخفیف خط',
    'tax_total'                   => 'مجموع مالیات',
    'total'                       => 'جمع کل',

    'item_name'                   => 'نام آیتم | نام آیتم ها',

    'show_discount'               => ':discount% تخفیف',
    'add_discount'                => 'افزودن تخفیف',
    'discount_desc'               => 'از جمع کل',

    'refund_from_vendor'          => 'بازپرداخت از فروشنده',
    'received_refund_from_vendor' => 'دریافت شده :amount باز پرداخت شده از :vendor',

    'histories'                   => 'تاریخچه',
    'type'                        => 'نوع',
    'refund'                      => 'باز پرداخت',
    'mark_sent'                   => 'علامت‌گذاری به عنوان ارسال شده',
    'receive_refund'              => 'دریافت بازپرداخت',
    'mark_viewed'                 => 'تغییر وضعیت به مشاهده شده',
    'mark_cancelled'              => 'تغییر وضعیت به لغو شده',
    'download_pdf'                => 'دانلود PDF',
    'send_mail'                   => 'ارسال ایمیل',
    'all_debit_notes'             => 'برای مشاهده همه یادداشتهای بدهی وارد شوید',
    'create_debit_note'           => 'ایجاد یادداشت بدهی',
    'send_debit_note'             => 'ارسال یادداشت بدهی',

    'statuses' => [
        'draft'     => 'پیش‌نویس',
        'sent'      => 'ارسال',
        'viewed'    => 'مشاهده شده',
        'approved'  => 'تایید شده',
        'partial'   => 'جزئی',
        'cancelled' => 'لغو شده',
    ],

    'messages' => [
        'email_sent'          => 'ایمیل یادداشت اعتباری ارسال شد!',
        'marked_sent'         => 'تغییر وضعیت یادداشت اعتباری به ارسال!',
        'marked_viewed'       => 'تغییر وضعیت یادداشت اعتباری به مشاهده شده!',
        'marked_cancelled'    => 'تغییر وضعیت یادداشت اعتباری به لغو شده!',
        'refund_was_received' => 'بازپرداخت دریافت شد!',
        'email_required'      => 'برای مشتری ایمیل ثبت نشده است!',
        'draft'               => 'این یک <b>پیشنویس</b> از یادداشت اعتباری است و پس از ارسال بر روی نمودار اعمال می شود.',

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
