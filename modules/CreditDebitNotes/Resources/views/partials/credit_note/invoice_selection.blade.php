{{ Form::selectGroup(
    'invoice_id',
    trans_choice('general.invoices', 1),
    'file-invoice-dollar',
    $invoices,
    $invoice_id,
    ['dynamicOptions' => 'invoices']
) }}
