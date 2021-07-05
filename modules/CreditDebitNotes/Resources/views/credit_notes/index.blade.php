@extends('layouts.admin')

@section('title', trans_choice('credit-debit-notes::general.credit_notes', 2))

@section('new_button')
    <x-documents.index.top-buttons
        type="credit-note"
        hide-import
        hide-export
        check-create-permission
    />
@endsection

@section('content')
    <x-documents.index.content
        type="credit-note"
        :documents="$credit_notes"
        hide-due-at
        url-docs-path="https://akaunting.com/docs/app-manual/accounting/credit-debit-notes"
    />
@endsection

@push('scripts_start')
    <x-documents.script type="credit-note"/>
@endpush
