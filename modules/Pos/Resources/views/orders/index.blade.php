@extends('layouts.admin')

@section('title', trans_choice('pos::general.orders', 2))

@section('new_button')
    <x-documents.index.top-buttons
        type="pos-order"
        hide-import
        hide-export
        hide-create
    />
@endsection

@section('content')
    <x-documents.index.content
        type="pos-order"
        :documents="$orders"
        hide-due-at
        hide-button-edit
        hide-button-duplicate
        text-modal-delete="pos::general.orders"
        url-docs-path="https://akaunting.com/docs/app-manual/point-of-sale/pos"
        create-route="pos.pos"
    />
@endsection

@push('scripts_start')
    <x-documents.script type="pos-order" />
@endpush
