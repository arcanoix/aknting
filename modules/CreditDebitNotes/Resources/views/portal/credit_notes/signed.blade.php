@extends('layouts.signed')

@section('title', trans_choice('credit-debit-notes::general.credit_notes', 1) . ': ' . $credit_note->document_number)

@section('new_button')
    @stack('button_print_start')
    <a href="{{ $print_action }}" target="_blank" class="btn btn-white btn-sm">
        {{ trans('general.print') }}
    </a>
    @stack('button_print_end')

    @stack('button_pdf_start')
    <a href="{{ $pdf_action }}" class="btn btn-white btn-sm">
        {{ trans('general.download') }}
    </a>
    @stack('button_pdf_end')

    @stack('button_dashboard_start')
    <a href="{{ route('portal.dashboard') }}" class="btn btn-white btn-sm">
        {{ trans('credit-debit-notes::credit_notes.all_credit_notes') }}
    </a>
    @stack('button_dashboard_end')
@endsection

@section('content')
    <x-documents.show.header
        type="credit-note"
        :document="$credit_note"
        hide-header-contact
        hide-header-due-at
        class-header-status="col-md-8"
    />

    <x-documents.show.document
        type="credit-note"
        :document="$credit_note"
        document-template="{{ setting('credit-debit-notes.credit_note.template', 'default') }}"
        hide-due-at
    />
@endsection

@push('footer_start')
    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    <script src="{{ asset('public/js/portal/invoices.js?v=' . version('short')) }}"></script>
@endpush
