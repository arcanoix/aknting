@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('credit-debit-notes::general.debit_notes', 1)]))

@section('content')
    <x-documents.form.content
        type="debit-note"
        :document="$debit_note"
        hide-company
        hide-footer
        hide-edit-item-columns
        hide-due-at
        hide-order-number
        hide-recurring
        hide-attachment
        is-purchase-price
    />
@endsection

@push('scripts_start')
    <x-documents.script
        type="debit-note"
        script-file="modules/CreditDebitNotes/Resources/assets/js/debit_notes/edit.min.js"
        :items="$debit_note->items"
        :document="$debit_note"
    />
@endpush
