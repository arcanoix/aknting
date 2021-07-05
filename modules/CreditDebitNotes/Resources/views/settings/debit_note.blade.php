@extends('layouts.admin')

@section('title', trans_choice('credit-debit-notes::general.debit_notes', 1))

@section('content')
    {!! Form::open([
        'id' => 'setting',
        'method' => 'PATCH',
        'route' => 'credit-debit-notes.settings.debit-note.update',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true,
    ]) !!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('number_prefix', trans('credit-debit-notes::settings.debit_note.prefix'), 'font', [], setting('credit-debit-notes.debit_note.number_prefix')) }}

                {{ Form::textGroup('number_digit', trans('credit-debit-notes::settings.debit_note.digit'), 'text-width', [], setting('credit-debit-notes.debit_note.number_digit')) }}

                {{ Form::textGroup('number_next', trans('credit-debit-notes::settings.debit_note.next'), 'chevron-right', [], setting('credit-debit-notes.debit_note.number_next')) }}
            </div>
        </div>

        @permission('update-credit-debit-notes-settings-debit-note')
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('settings.index') }}
                </div>
            </div>
        @endpermission
    </div>
    {!! Form::hidden('_prefix', 'credit-debit-notes.debit_note') !!}

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/CreditDebitNotes/Resources/assets/js/debit_notes/settings.min.js?v=' . module_version('credit-debit-notes')) }}"></script>
@endpush
