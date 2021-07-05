<?php

return [

    'credit_note_new_customer' => [
        'subject' => '{credit_note_number} credit note created',
        'body'    => 'Dear {customer_name},<br /><br />We have prepared the following credit note for you: <strong>{credit_note_number}</strong>.<br /><br />You can see the credit note details from the following link: <a href="{credit_note_guest_link}">{credit_note_number}</a>.<br /><br />Feel free to contact us for any question.<br /><br />Best Regards,<br />{company_name}',
    ],

    'debit_note_new_customer' => [
        'subject' => '{debit_note_number} debit note created',
        'body'    => 'Dear {vendor_name},<br /><br />We have prepared the following debit note for you: <strong>{debit_note_number}</strong>.<br /><br />You can see the debit note details from the following link: <a href="{debit_note_guest_link}">{debit_note_number}</a>.<br /><br />Feel free to contact us for any question.<br /><br />Best Regards,<br />{company_name}',
    ],

];
