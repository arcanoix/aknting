<?php

return [


    'pagesize'=>[
        'a4Horizontal'=>'A4水平',
        'a4Vertical'=>'A4垂直',
        'a5Horizontal'=>'A5水平',
        'a5Vertical'=>'A4垂直',
        'Letter'=>'Letter',
        'Ledger'=>'Ledger',
        'Legal'=>'Legal',
    ],

    'type'=>[
        'invoice'=>'請求書',
        'bill'=>'法案',
    ],

    'category'=>[
        'company_info'=>"会社の詳細",
        'customer_info'=>"顧客の詳細",
        'invoice_info'=>"請求の詳細",
        'invoiceitem_info'=>"請求書アイテム",
        'invoicetotal_info'=>"請求書合計",
    ],
    
    'items'=>[
        'company_name'         =>'会社名',
        'company_address'      =>'会社の住所',
        'company_tax_number'   =>'会社の税番号',
        'company_phone'        =>'会社の電話番号',
        'company_email'        =>'会社の電子メール',

        
        'customer_name'         =>'顧客名',
        'customer_email'        =>'顧客の電子メール',
        'customer_tax_number'   =>'顧客税番号 ',
        'customer_phone'        =>'お客様の電話 ',
        'customer_website'      =>'お客様のウェブサイト',
        'customer_address'      =>'顧客住所',
        'customer_reference'    =>'顧客参照',

        'invoice_number'        =>'請求書番号',
        'order_number'          =>'注文番号',

        'invoiced_at'           =>'請求書の日付と時間',
        'invoiced_at_date'      =>'請求書の日付',
        'invoiced_at_hour'      =>'請求時間',

        'due_at'                =>'期日と時間',
        'due_at_date'           =>'期日',
        'due_at_hour'           =>'期限',
        'notes'                 =>'請求書メモ',

        'item_name'             =>'項目名',
        'item_sku'              =>'SKU',
        'item_quantity'         =>'数量',
        'item_price'            =>'価格',
        'item_total'            =>'合計',
        'item_tax'              =>'消費税',

        'total_sub_total'           =>'小計',
        'total_tax'                 =>'消費税',
        'total_discount'            =>'割引',
        'total_total'               =>'合計',
        'total_total_word'          =>'総消費税',
        'total_sub_total_with_type' =>'タイプ付き小計',
        'total_tax_with_type'       =>'タイプの税金',
        'total_total_with_type'     =>'タイプ付き合計',
        'total_discount_with_type'  =>'タイプによる割引',
        'tax_only_name'       =>'税金-名前のみ',
        'paid'                      =>'Paid',
        'paid_only_name'            =>'Paid (Only Text)',
        'paid_with_type'            =>'Paid (With Text)',
        'amount'                    =>'Amount',
        'amount_only_name'          =>'Amount (Only Text)',
        'amount_with_type'          =>'Amount (With Text)',
    ]

];
