@extends('layouts.admin')

@section('title', trans('print-template::general.title'))

@section('content')
<!-- Default card -->
<div class="card ">
    <div class="card-header with-border">
        <h3 class="card-title">{{ trans('print-template::general.header_create') }}</h3>
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    {!! Form::open([
    'id' => 'print-template',
    'route' => 'print-template.settings.store',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true,
    ]) !!}
    <div class="card-body">
        <div class="row">
            {{ Form::textGroup('name', trans('print-template::general.form.name'), 'barcode', ['required' => 'required']) }}
            {{ Form::selectGroup('type', trans_choice('print-template::general.form.type', 1), 'file', $type,null) }}
            {{ Form::selectGroup('pagesize', trans_choice('print-template::general.form.pagesize', 1), 'font', $pagesize) }}
            {{ Form::fileGroup('attachment', trans('print-template::general.form.attachment'),null, ['required' => 'required']) }}
            {{ Form::radioGroup('enabled', trans('general.enabled'),1) }}
            {{ Form::radioGroup('printBackground', trans('print-template::general.printBackground'),0) }}
        </div>
    </div>
    <!-- /.card-body -->

    <div class="card-footer">
        <div class="row save-buttons">
            {{ Form::saveButtons('settings/print-template') }}
        </div>
    </div>
    <!-- /.card-footer -->

    {!! Form::close() !!}

</div>
<!-- /.card -->
@endsection

@push('scripts_start')
<script src="{{ asset('modules/PrintTemplate/Resources/assets/js/print-template.min.js?v=' . version('short')) }}">
</script>
@endpush