@can('create-credit-debit-notes-debit-notes')
    @if ($bill->status !== 'draft')
    <a href="{{ route('credit-debit-notes.debit-notes.create', ['bill' => $bill->id]) }}" class="btn btn-white btn-sm">
        {{ trans('general.title.create', ['type' => trans_choice('credit-debit-notes::general.debit_notes', 1)]) }}
    </a>
    @endif
@endcan
