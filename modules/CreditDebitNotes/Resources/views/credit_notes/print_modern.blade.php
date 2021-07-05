@extends('layouts.print')

@section('title', trans_choice('credit-debit-notes::general.credit_notes', 1) . ': ' . $credit_note->document_number)

@section('content')
    <x-documents.template.modern
        type="credit-note"
        :document="$credit_note"
        hide-due-at
    />
@endsection
