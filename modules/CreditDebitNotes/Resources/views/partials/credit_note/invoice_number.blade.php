<strong>
    {{ trans('credit-debit-notes::credit_notes.related_invoice_number') }}:
</strong>
<span class="float-right">
    @if(!$print)
    <a href="{{ $invoice_route }}">
    @endif
    {{ $credit_note->invoice_number }}
    @if(!$print)
    </a>
    @endif
</span><br><br>
