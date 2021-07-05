<div class="tab-pane fade" id="credit-notes-content" role="tabpanel" aria-labelledby="credit-notes-tab">
    <div class="table-responsive">
        <table class="table table-flush table-hover" id="tbl-credit-notes">
            <thead class="thead-light">
            <tr class="row table-head-line">
                <th class="col-xs-4 col-sm-2">{{ trans_choice('general.numbers', 1) }}</th>
                <th class="col-xs-4 col-sm-3 text-right">{{ trans('general.amount') }}</th>
                <th class="col-sm-4 d-none d-sm-block text-left">{{ trans('credit-debit-notes::credit_notes.credit_note_date') }}</th>
                <th class="col-xs-4 col-sm-3">{{ trans_choice('general.statuses', 1) }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($credit_notes as $item)
                <tr class="row align-items-center border-top-1 tr-py">
                    <td class="col-xs-4 col-sm-2"><a href="{{ route('credit-debit-notes.credit-notes.show', $item->id) }}">{{ $item->document_number }}</a></td>
                    <td class="col-xs-4 col-sm-3 text-right">@money($item->amount, $item->currency_code, true)</td>
                    <td class="col-sm-4 d-none d-sm-block text-left">@date($item->issued_at)</td>
                    <td class="col-xs-4 col-sm-3"><span class="badge badge-pill badge-{{ $item->status_label }} my--2">{{ trans('credit-debit-notes::credit_notes.statuses.' . $item->status) }}</span></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer py-4 table-action">
        <div class="row">
            @include('partials.admin.pagination', ['items' => $credit_notes])
        </div>
    </div>
</div>
