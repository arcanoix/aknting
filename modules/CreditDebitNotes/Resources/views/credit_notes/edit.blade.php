@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('credit-debit-notes::general.credit_notes', 1)]))

@section('content')
    <x-documents.form.content
        type="credit-note"
        :document="$credit_note"
        hide-company
        hide-footer
        hide-edit-item-columns
        hide-due-at
        hide-order-number
        hide-recurring
        hide-attachment
    />
@endsection

@push('scripts_start')
    <x-documents.script
        type="credit-note"
        script-file="modules/CreditDebitNotes/Resources/assets/js/credit_notes/edit.min.js"
        :items="$credit_note->items"
        :document="$credit_note"
    />
@endpush
