<?php

return [

    'credit_note_new_customer' => [
        'subject' => '{credit_note_number} credit note created',
        'body'    => 'Dear {customer_name},<br /><br />We have prepared the following credit note for you: <strong>{credit_note_number}</strong>.<br /><br />You can see the credit note details from the following link: <a href="{credit_note_guest_link}">{credit_note_number}</a>.<br /><br />Feel free to contact us for any question.<br /><br />Best Regards,<br />{company_name}',
    ],

];
