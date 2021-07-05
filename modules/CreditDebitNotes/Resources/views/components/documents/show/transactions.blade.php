<div class="accordion">
    <div class="card">
        <div class="card-header" id="accordion-transactions-header" data-toggle="collapse" data-target="#accordion-transactions-body" aria-expanded="false" aria-controls="accordion-transactions-body">
            <h4 class="mb-0">{{ trans_choice('general.transactions', 2) }}</h4>
        </div>

        <div id="accordion-transactions-body" class="collapse hide" aria-labelledby="accordion-transactions-header">
            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        @stack('row_footer_transactions_head_tr_start')
                        <tr class="row table-head-line">
                            @stack('row_footer_transactions_head_td_start')
                            <th class="col-xs-4 col-sm-3">
                                {{ trans('general.date') }}
                            </th>
                            <th class="col-xs-2 col-sm-2">
                                {{ trans('credit-debit-notes::debit_notes.type') }}
                            </th>
                            <th class="col-xs-3 col-sm-2">
                                {{ trans('general.amount') }}
                            </th>
                            <th class="col-sm-3 d-none d-sm-block">
                                {{ trans_choice('general.accounts', 1) }}
                            </th>
                            <th class="col-xs-3 col-sm-2">
                                {{ trans('general.actions') }}
                            </th>
                            @stack('row_footer_transactions_head_td_end')
                        </tr>
                        @stack('row_footer_transactions_head_tr_end')
                    </thead>
                    <tbody>
                        @stack('row_footer_transactions_body_tr_start')
                        @if ($transactions->count())
                            @foreach($transactions as $transaction)
                                <tr class="row align-items-center border-top-1 tr-py">
                                    @stack('row_footer_transactions_body_td_start')
                                    <td class="col-xs-4 col-sm-3">
                                        @date($transaction->paid_at)
                                    </td>
                                    <td class="col-xs-2 col-sm-2">
                                        {{ $transaction->type }}
                                    </td>
                                    <td class="col-xs-3 col-sm-2">
                                        @money($transaction->amount, $transaction->currency_code, true)
                                    </td>
                                    <td class="col-sm-3 d-none d-sm-block">
                                        {{ $transaction->account ? $transaction->account->name : '' }}
                                    </td>
                                    <td class="col-xs-3 col-sm-2 py-0">
                                        @if ($transaction->reconciled)
                                            <button type="button" class="btn btn-default btn-sm">
                                                {{ trans('reconciliations.reconciled') }}
                                            </button>
                                        @else
                                            @php
                                                $route = route($transaction->type === trans('credit-debit-notes::credit_notes.credit') ? 'credit-debit-notes.credits-transactions.destroy' : 'transactions.destroy', $transaction->id);

                                                $message = trans('general.delete_confirm', [
                                                    'name' => '<strong>' . Date::parse($transaction->paid_at)->format($date_format) . ' - ' . money($transaction->amount, $transaction->currency_code, true) . ' - ' . ($transaction->account ? $transaction->account->name : '') . '</strong>',
                                                    'type' => strtolower(trans_choice('general.transactions', 1))
                                                ]);
                                            @endphp

                                            {!! Form::button(trans('general.delete'), array(
                                                'type'    => 'button',
                                                'class'   => 'btn btn-danger btn-sm',
                                                'title'   => trans('general.delete'),
                                                '@click'  => 'confirmDelete("' . $route . '", "' . trans_choice('general.transactions', 2) . '", "' . $message. '",  "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
                                            )) !!}
                                        @endif
                                    </td>
                                    @stack('row_footer_transactions_body_td_end')
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
                        @stack('row_footer_transactions_body_tr_end')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
