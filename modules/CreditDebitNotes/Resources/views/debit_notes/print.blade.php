@extends('layouts.print')

@section('title', trans_choice('credit-debit-notes::general.debit_notes', 1) . ': ' . $debit_note->document_number)

@section('content')
    <x-documents.template.ddefault
        type="debit-note"
        :document="$debit_note"
        hide-due-at
    />
@endsection
