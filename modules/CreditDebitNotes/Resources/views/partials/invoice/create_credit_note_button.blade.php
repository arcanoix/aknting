@can('create-credit-debit-notes-credit-notes')
    @if ($invoice->status !== 'draft')
    <a href="{{ route('credit-debit-notes.credit-notes.create', ['invoice' => $invoice->id]) }}" class="btn btn-white btn-sm">
        {{ trans('general.title.create', ['type' => trans_choice('credit-debit-notes::general.credit_notes', 1)]) }}
    </a>
    @endif
@endcan
