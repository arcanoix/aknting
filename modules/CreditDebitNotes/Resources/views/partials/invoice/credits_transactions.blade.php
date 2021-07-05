<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
    <div class="accordion">
        <div class="card">
            <div class="card-header" id="accordion-credits-transactions-header" data-toggle="collapse" data-target="#accordion-credits-transactions-body" aria-expanded="false" aria-controls="accordion-credits-transactions-body">
                <h4 class="mb-0">{{ trans_choice('credit-debit-notes::invoices.credits', 2) }} {{ trans_choice('general.transactions', 2) }}</h4>
            </div>
            <div id="accordion-credits-transactions-body" class="collapse hide" aria-labelledby="accordion-credits-transactions-header">
                <div class="table-responsive">
                    <table class="table table-flush table-hover">
                        <thead class="thead-light">
                            <tr class="row table-head-line">
                                <th class="col-xs-4 col-sm-4">{{ trans('general.date') }}</th>
                                <th class="col-xs-4 col-sm-4">{{ trans('general.amount') }}</th>
                                <th class="col-xs-4 col-sm-4">{{ trans('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($transactions->count())
                                @foreach($transactions as $transaction)
                                    <tr class="row align-items-center border-top-1 tr-py">
                                        <td class="col-xs-4 col-sm-4">@date($transaction->paid_at)</td>
                                        <td class="col-xs-4 col-sm-4">@money($transaction->amount, $transaction->currency_code, true)</td>
                                        <td class="col-xs-4 col-sm-4 py-0">
                                            @php $message = trans('general.delete_confirm', [
                                                    'name' => '<strong>' . Date::parse($transaction->paid_at)->format($date_format) . ' - ' . money($transaction->amount, $transaction->currency_code, true) . '</strong>',
                                                    'type' => strtolower(trans_choice('general.transactions', 1))
                                                    ]);
                                            @endphp

                                            {!! Form::button(trans('general.delete'), array(
                                                'type'    => 'button',
                                                'class'   => 'btn btn-danger btn-sm',
                                                'title'   => trans('general.delete'),
                                                '@click'  => 'confirmDelete("' . route('credit-debit-notes.credits-transactions.destroy', $transaction->id) . '", "' . trans_choice('credit-debit-notes::invoices.credits', 2) . ' ' . trans_choice('general.transactions', 2) . '", "' . $message. '",  "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
                                            )) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        <div class="text-muted nr-py" id="datatable-basic_info" role="status" aria-live="polite">
                                            {{ trans('general.no_records') }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
