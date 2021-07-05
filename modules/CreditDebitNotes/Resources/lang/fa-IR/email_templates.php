<?php

return [

    'credit_note_new_customer' => [
        'subject' => '{credit_note_number} یادداشت اعتباری ایجاد شد',
        'body'    => 'عزیز {customer_name},<br /><br />یک یادداشت اعتباری برای شما آماده کرده ایم you: <strong>{credit_note_number}</strong>.<br /><br />چزئیات این یادداشت را در این لینک ببینید: <a href="{credit_note_guest_link}">{credit_note_number}</a>.<br /><br />همیشه آماده پاسخگویی به سوالات شما هستیم.<br /><br />با احترام,<br />{company_name}',
    ],

    'debit_note_new_customer' => [
        'subject' => '{debit_note_number} یادداشت اعتباری ایجاد شد',
        'body'    => 'عزیز {vendor_name},<br /><br />یک یادداشت بدهی برای شما آماده کرده ایم: <strong>{debit_note_number}</strong>.<br /><br />جزئیات این یادداشت را میتوانید در لینک آورده شده ببینید: <a href="{debit_note_guest_link}">{debit_note_number}</a>.<br /><br />همیشه آماده پاسخگویی به سوالات شما هستیم.<br /><br />با احترام,<br />{company_name}',
    ],

];
