<?php

return [

    'pageSize'=>[
        'A4-H' => trans('print-template::sablon.pagesize.a4Horizontal'),
        'A4-V' => trans('print-template::sablon.pagesize.a4Vertical'),
        'A5-H' => trans('print-template::sablon.pagesize.a5Horizontal'),
        'A5-V' => trans('print-template::sablon.pagesize.a5Vertical'),  
        'Letter'=>trans('print-template::sablon.pagesize.Letter'),
        'Ledger'=>trans('print-template::sablon.pagesize.Ledger'),
        'Legal'=>trans('print-template::sablon.pagesize.Legal'),
    ],

    'pageSize-mm'=>[ //X - Y
        'A4-V'=>array("210","297"),
        'A4-H'=>array("297","210"),
        'A5-V'=>array("148","210"),
        'A5-H'=>array("210","148"),
        'Letter'=>array("215.9","279.4"),
        'Ledger'=>array("279.4","431.8"),
        'Legal'=>array("215.9","355.6"),
    ],

      'pageSize-type'=>[ //CssName - portrait
        'A4-V'=>array("A4","portrait"),
        'A4-H'=>array("A4","landscape"),
        'A5-V'=>array("A5","portrait"),
        'A5-H'=>array("A5","landscape"),
        'Letter'=>array("Letter","portrait"),
        'Ledger'=>array("Ledger","portrait"),
        'Legal'=>array("Legal","portrait"),
    ],


    'printType' => [
        'invoice' => trans('print-template::sablon.type.invoice'),
    //    'bill' => trans('print-template::general.type.bill'),
    ],

    'basamaklar'=>[
        'birlik'  => ['Bir', 'İki', 'Üç', 'Dört', 'Beş', 'Altı', 'Yedi', 'Sekiz', 'Dokuz'],
        'onluk'   => ['On', 'Yirmi', 'Otuz', 'Kırk', 'Elli', 'Altmış', 'Yetmiş', 'Seksen', 'Doksan'],
        'basamak' => ['Yüz', 'Bin', 'Milyon', 'Milyar', 'Trilyon', 'Katrilyon']
    ],

    'printItemsCategory'=>[
        trans("print-template::sablon.category.company_info")=>range(1,5),
        trans("print-template::sablon.category.customer_info")=>range(6,12),
        trans("print-template::sablon.category.invoice_info")=>range(13,21),
        trans("print-template::sablon.category.invoiceitem_info")=>array_merge(range(22,27),range(44,45)),
        trans("print-template::sablon.category.invoicetotal_info")=>array_merge(range(28,43),range(50,52)),
    ],

    'printItems' => [
        "1"=>array("type"=>"invoice","field"=>"company","name"=>"name","display_name"=>trans("print-template::sablon.items.company_name")),
        "2"=>array("type"=>"invoice","field"=>"company","name"=>"address","display_name"=>trans("print-template::sablon.items.company_address")),
        "3"=>array("type"=>"invoice","field"=>"company","name"=>"tax_number","display_name"=>trans("print-template::sablon.items.company_tax_number")),
        "4"=>array("type"=>"invoice","field"=>"company","name"=>"phone","display_name"=>trans("print-template::sablon.items.company_phone")),
        "5"=>array("type"=>"invoice","field"=>"company","name"=>"email","display_name"=>trans("print-template::sablon.items.company_email")),

        "6"=>array("type"=>"invoice","field"=>"customer","name"=>"name","display_name"=>trans("print-template::sablon.items.customer_name")),
        "7"=>array("type"=>"invoice","field"=>"customer","name"=>"tax_number","display_name"=>trans("print-template::sablon.items.customer_tax_number")),
        "8"=>array("type"=>"invoice","field"=>"customer","name"=>"phone","display_name"=>trans("print-template::sablon.items.customer_phone")),
        "9"=>array("type"=>"invoice","field"=>"customer","name"=>"email","display_name"=>trans("print-template::sablon.items.customer_email")),
        "10"=>array("type"=>"invoice","field"=>"customer","name"=>"address","display_name"=>trans("print-template::sablon.items.customer_address")),
        "11"=>array("type"=>"invoice","field"=>"customer","name"=>"reference","display_name"=>trans("print-template::sablon.items.customer_reference")),
        "12"=>array("type"=>"invoice","field"=>"customer","name"=>"website","display_name"=>trans("print-template::sablon.items.customer_website")),

        "13"=>array("type"=>"invoice","field"=>"invoice","name"=>"invoice_number","display_name"=>trans("print-template::sablon.items.invoice_number")),
        "14"=>array("type"=>"invoice","field"=>"invoice","name"=>"order_number","display_name"=>trans("print-template::sablon.items.order_number")),
        "15"=>array("type"=>"invoice","field"=>"invoice","name"=>"notes","display_name"=>trans("print-template::sablon.items.notes")),
        "16"=>array("type"=>"invoice","field"=>"invoice","name"=>"invoiced_at","display_name"=>trans("print-template::sablon.items.invoiced_at")),
        "17"=>array("type"=>"invoice","field"=>"invoice","name"=>"due_at","display_name"=>trans("print-template::sablon.items.due_at")),

        "18"=>array("type"=>"invoice","field"=>"custom","name"=>"invoiced_at_date","display_name"=>trans("print-template::sablon.items.invoiced_at_date")),
        "19"=>array("type"=>"invoice","field"=>"custom","name"=>"invoiced_at_hour","display_name"=>trans("print-template::sablon.items.invoiced_at_hour")),
        "20"=>array("type"=>"invoice","field"=>"custom","name"=>"due_at_date","display_name"=>trans("print-template::sablon.items.due_at_date")),
        "21"=>array("type"=>"invoice","field"=>"custom","name"=>"due_at_hour","display_name"=>trans("print-template::sablon.items.due_at_hour")),

        "22"=>array("type"=>"invoice","field"=>"item","name"=>"name","display_name"=>trans("print-template::sablon.items.item_name")),
        "23"=>array("type"=>"invoice","field"=>"item","name"=>"sku","display_name"=>trans("print-template::sablon.items.item_sku")),
        "24"=>array("type"=>"invoice","field"=>"item","name"=>"quantity","display_name"=>trans("print-template::sablon.items.item_quantity")),
        "25"=>array("type"=>"invoice","field"=>"item","name"=>"price","display_name"=>trans("print-template::sablon.items.item_price")),
        "26"=>array("type"=>"invoice","field"=>"item","name"=>"total","display_name"=>trans("print-template::sablon.items.item_total")),
        "27"=>array("type"=>"invoice","field"=>"item","name"=>"tax","display_name"=>trans("print-template::sablon.items.item_tax")),

        "28"=>array("type"=>"invoice","field"=>"total","name"=>"sub_total","display_name"=>trans("print-template::sablon.items.total_sub_total")),
        "29"=>array("type"=>"invoice","field"=>"total","name"=>"tax","display_name"=>trans("print-template::sablon.items.total_tax")),
        "30"=>array("type"=>"invoice","field"=>"total","name"=>"total","display_name"=>trans("print-template::sablon.items.total_total")),
        "31"=>array("type"=>"invoice","field"=>"total","name"=>"discount","display_name"=>trans("print-template::sablon.items.total_discount")),
        
        "32"=>array("type"=>"invoice","field"=>"custom","name"=>"total_word","display_name"=>trans("print-template::sablon.items.total_total_word")),
        "33"=>array("type"=>"invoice","field"=>"custom","name"=>"sub_total_with_type","display_name"=>trans("print-template::sablon.items.total_sub_total_with_type")),
        "34"=>array("type"=>"invoice","field"=>"custom","name"=>"tax_with_type","display_name"=>trans("print-template::sablon.items.total_tax_with_type")),
        "35"=>array("type"=>"invoice","field"=>"custom","name"=>"total_with_type","display_name"=>trans("print-template::sablon.items.total_total_with_type")),
        "36"=>array("type"=>"invoice","field"=>"custom","name"=>"discout_with_type","display_name"=>trans("print-template::sablon.items.total_discount_with_type")),
        "37"=>array("type"=>"invoice","field"=>"custom","name"=>"tax_only_name","display_name"=>trans("print-template::sablon.items.tax_only_name")),
        "38"=>array("type"=>"invoice","field"=>"custom","name"=>"paid","display_name"=>trans("print-template::sablon.items.paid")),
        "39"=>array("type"=>"invoice","field"=>"custom","name"=>"paid_only_name","display_name"=>trans("print-template::sablon.items.paid_only_name")),
        "40"=>array("type"=>"invoice","field"=>"custom","name"=>"paid_with_type","display_name"=>trans("print-template::sablon.items.paid_with_type")),
        "41"=>array("type"=>"invoice","field"=>"custom","name"=>"amount","display_name"=>trans("print-template::sablon.items.amount")),
        "42"=>array("type"=>"invoice","field"=>"custom","name"=>"amount_only_name","display_name"=>trans("print-template::sablon.items.amount_only_name")),
        "43"=>array("type"=>"invoice","field"=>"custom","name"=>"amount_with_type","display_name"=>trans("print-template::sablon.items.amount_with_type")),


        "44"=>array("type"=>"invoice","field"=>"item","name"=>"description","display_name"=>trans("print-template::sablon.items.item_description")),
        "45"=>array("type"=>"invoice","field"=>"item","name"=>"discount","display_name"=>trans("print-template::sablon.items.item_discount")),
        //46
        //47
        //48
        //49. reserved for invoice-item
        "50"=>array("type"=>"invoice","field"=>"total","name"=>"item_discount","display_name"=>trans("print-template::sablon.items.line_discount")),
        "51"=>array("type"=>"invoice","field"=>"custom","name"=>"item_discount_only_name","display_name"=>trans("print-template::sablon.items.line_discount_only_name")),
        "52"=>array("type"=>"invoice","field"=>"custom","name"=>"item_discount_with_type","display_name"=>trans("print-template::sablon.items.line_discount_with_type")),
        //53
        //54
        //56
        //57
        //58
        //59
        //60. reserved for invoice-total
        
		
		//1000-1100 reserved for CustomFields Modules
    ]

];
