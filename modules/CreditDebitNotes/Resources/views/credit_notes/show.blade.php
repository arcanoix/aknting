@extends('layouts.admin')

@section('title', trans_choice('credit-debit-notes::general.credit_notes', 1) . ': ' . $credit_note->document_number)

@section('new_button')
    @push('add_new_button_start')
        @if ($credit_note->status === 'sent' && ! $credit_note->credit_customer_account)
            <button id="button-make-refund" class="btn btn-white btn-sm">
                {{ trans('credit-debit-notes::credit_notes.make_refund') }}
            </button>
        @endif
    @endpush

    <x-documents.show.top-buttons
        type="credit-note"
        :document="$credit_note"
        {{--        TODO: add getting this variable's value based on type --}}
        text-delete-modal="credit-debit-notes::general.credit_notes"
    />
@endsection

@section('content')
    <x-documents.show.content
        type="credit-note"
        :document="$credit_note"
        hide-due-at
        hide-header-due-at
        hide-button-received
        hide-button-email
        hide-timeline-paid
    />
@endsection

@push('body_end')
    <div id="credit-debit-notes-vue-entrypoint">
        <component v-bind:is="component"></component>
    </div>
@endpush

@push('scripts_start')
    <script type="text/javascript">
        var envelopeBadge = document.querySelector('span.timeline-step.badge-danger')

        if (envelopeBadge) {
            envelopeBadge.className = 'timeline-step badge-success'
        }
    </script>

    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">

    <x-documents.script
        type="credit-note"
        script-file="modules/CreditDebitNotes/Resources/assets/js/credit_notes/show.min.js"
    />
@endpush
