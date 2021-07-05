@extends('layouts.portal')

@section('title', trans_choice('credit-debit-notes::general.credit_notes', 2))

@section('content')
    <x-documents.index.content
        type="credit-note"
        :documents="$credit_notes"
        hide-bulk-action
        hide-contact-name
        hide-actions
        hide-empty-page
        hide-due-at
        form-card-header-route="portal.invoices.index"
        route-button-show="portal.credit-debit-notes.credit-notes.show"
        class-document-number="col-xs-4 col-sm-4 col-md-3 disabled-col-aka"
        class-amount="col-xs-4 col-sm-2 col-md-3 text-right"
        class-issued-at="col-sm-3 col-md-3 d-none d-sm-block"
        class-status="col-xs-4 col-sm-3 col-md-3 text-center"
        search-string-model="Modules\CreditDebitNotes\Models\Portal\CreditNote"
    />
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/portal/invoices.js?v=' . version('short')) }}"></script>
@endpush
