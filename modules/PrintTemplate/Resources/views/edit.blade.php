@extends('layouts.admin')

@section('title', trans('print-template::general.title'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Default card -->
        <div class="card card-success">
            <div class="card-header with-border">
                <h3 class="card-title">{{ trans('print-template::general.header_edit') }}</h3>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->

            {!! Form::model($print_template, [
            'id' => 'print-template',
            'method' => 'PATCH',
            'route' => ['print-template.settings.edit', $print_template->id],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
            ]) !!}


            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('print-template::general.form.name'), 'barcode', ['required' => 'required']) }}
                    {{ Form::selectGroup('type', trans_choice('print-template::general.form.type', 1), 'file', $types,$print_template->type) }}
                    {{ Form::selectGroup('pagesize', trans_choice('print-template::general.form.pagesize', 1), 'font', $pagesizes,$print_template->pagesize) }}
                    {{ Form::fileGroup('attachment', trans('print-template::general.form.attachment')) }}
                    {{ Form::radioGroup('enabled', trans('general.enabled'),$print_template->enabled) }}
                    {{ Form::radioGroup('printBackground', trans('print-template::general.printBackground'),$print_template->printBackground) }}
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
    </div>
</div>
@endsection

@push('scripts_start')
<script src="{{ asset('modules/PrintTemplate/Resources/assets/js/print-template.min.js?v=' . version('short')) }}">
</script>
@endpush