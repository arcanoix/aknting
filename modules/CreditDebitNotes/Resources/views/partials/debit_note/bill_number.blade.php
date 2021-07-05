@if($debit_note->bill_number)
    <strong>
        {{ trans('credit-debit-notes::debit_notes.related_bill_number') }}:
    </strong>
    <span class="float-right">
        @if(!$print)
        <a href="{{ route('bills.show', ['bill' => $debit_note->bill_id]) }}">
        @endif
        {{ $debit_note->bill_number }}
        @if(!$print)
        </a>
        @endif
    </span><br><br>
@endif
