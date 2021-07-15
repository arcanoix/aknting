@extends('layouts.admin')

@section('title', trans_choice('pos::general.orders', 1) . ': ' . $order->document_number)

@section('new_button')
    <x-documents.show.top-buttons
        type="pos-order"
        :document="$order"
        hide-button-edit
        hide-button-duplicate
        hide-button-group-divider-1
        hide-button-print
        hide-button-share
        hide-button-email
        hide-button-pdf
        hide-button-customize
        hide-button-group-divider-3
        hide-button-add-new
        text-delete-modal="pos::general.orders"
    />
@endsection

@section('content')
    <x-documents.show.content
        type="pos-order"
        :document="$order"
        hide-header-due-at
        hide-button-received
        hide-button-email
        hide-timeline
        hide-due-at
{{--        TODO: adjust the totals to correctly show the paid amount and change--}}
    />
@endsection

@push('scripts_start')
    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">

    <x-documents.script type="pos-order" />
@endpush


