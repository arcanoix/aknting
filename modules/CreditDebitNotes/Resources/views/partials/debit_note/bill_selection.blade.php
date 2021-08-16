{{ Form::selectGroup(
    'bill_id',
    trans_choice('general.bills', 1),
    'file-invoice-dollar',
    $bills,
    $bill_id,
    ['dynamicOptions' => 'bills']
) }}
