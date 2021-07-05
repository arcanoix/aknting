<?php

return [

    'credit_note_new_customer' => [
        'subject' => 'Kreditnota nr. {credit_note_number} oprettet',
        'body'    => 'Kære {customer_name},<br /><br />Vi har udarbejdet følgende kreditnota til dig: <strong>{credit_note_number}</strong>.<br /><br />Du kan se kreditnotaens detaljer på følgende link: <a href="{credit_note_guest_link}">{credit_note_number}</a>.<br /><br />Du er velkommen til at kontakte os ved ethvert spørgsmål.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'debit_note_new_customer' => [
        'subject' => 'Debetnota nr. {debit_note_number} oprettet',
        'body'    => 'Kære {vendor_name},<br /><br />Vi har udarbejdet følgende debetnota til dig: <strong>{debit_note_number}</strong>.<br /><br />Du kan se debetnotedetaljer fra følgende link: <a href="{debit_note_guest_link}">{debit_note_number}</a>.<br /><br />Du er velkommen til at kontakte os ved ethvert spørgsmål.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

];
