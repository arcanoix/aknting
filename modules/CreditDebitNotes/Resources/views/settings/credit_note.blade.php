@extends('layouts.admin')

@section('title', trans_choice('credit-debit-notes::general.credit_notes', 1))

@section('content')
    {!! Form::open([
        'id' => 'setting',
        'method' => 'PATCH',
        'route' => 'credit-debit-notes.settings.credit-note.update',
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
                {{ Form::textGroup('number_prefix', trans('credit-debit-notes::settings.credit_note.prefix'), 'font', [], setting('credit-debit-notes.credit_note.number_prefix')) }}

                {{ Form::textGroup('number_digit', trans('credit-debit-notes::settings.credit_note.digit'), 'text-width', [], setting('credit-debit-notes.credit_note.number_digit')) }}

                {{ Form::textGroup('number_next', trans('credit-debit-notes::settings.credit_note.next'), 'chevron-right', [], setting('credit-debit-notes.credit_note.number_next')) }}

                {{ Form::textGroup('title', trans('credit-debit-notes::settings.credit_note.title'), 'font', [], setting('credit-debit-notes.credit_note.title')) }}

                {{ Form::textGroup('subheading', trans('credit-debit-notes::settings.credit_note.subheading'), 'font', [], setting('credit-debit-notes.credit_note.subheading')) }}

                {{ Form::textareaGroup('notes', trans_choice('general.notes', 2), 'sticky-note-o', setting('credit-debit-notes.credit_note.notes')) }}

                {{ Form::textareaGroup('footer', trans('general.footer'), 'sticky-note-o', setting('credit-debit-notes.credit_note.footer')) }}

{{--                TODO: allow to set a custom text for item price and quantity--}}
{{--                {{ Form::textGroup('item_name', trans('credit-debit-notes::settings.credit_note.item_name'), 'font', []) }}--}}

{{--                {{ Form::textGroup('price_name', trans('credit-debit-notes::settings.credit_note.price_name'), 'font', []) }}--}}

{{--                {{ Form::textGroup('quantity_name', trans('credit-debit-notes::settings.credit_note.quantity_name'), 'font', []) }}--}}

                @permission('update-credit-debit-notes-settings-credit-note')
                    <div class="form-group col-md-6">
                        {!! Form::label('credit_note_template', trans_choice('general.templates', 1), ['class' => 'form-control-label']) !!}

                        <div class="input-group">
                            <button type="button" class="btn btn-block btn-outline-primary" @click="onTemplate">
                                <i class="fas fa-palette"></i>&nbsp; {{ trans('credit-debit-notes::settings.credit_note.choose_template') }}
                            </button>
                        </div>
                    </div>
                @endpermission
            </div>
        </div>

        @permission('update-credit-debit-notes-settings-credit-note')
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('settings.index') }}
                </div>
            </div>
        @endpermission
    </div>
    {!! Form::hidden('_prefix', 'credit-debit-notes.credit_note') !!}

    {!! Form::close() !!}
@endsection

@push('content_content_end')
    <akaunting-modal
        :show="template.modal"
        @cancel="template.modal = false"
        :title="'{{ trans('credit-debit-notes::settings.credit_note.choose_template') }}'"
        :message="template.html"
        :button_cancel="'{{ trans('general.button.save') }}'"
        :button_delete="'{{ trans('general.button.cancel') }}'">
        <template #modal-body>
            @include('credit-debit-notes::modals.settings.credit_note_template')
        </template>

        <template #card-footer>
            <div class="float-right">
                <button type="button" class="btn btn-outline-secondary" @click="closeTemplate">
                    {{ trans('general.cancel') }}
                </button>

                <button :disabled="formCreditNoteTemplate.loading"  type="button" class="btn btn-success button-submit" @click="addTemplate">
                    <div class="aka-loader"></div><span>{{ trans('general.confirm') }}</span>
                </button>
            </div>
        </template>
    </akaunting-modal>
@endpush

@push('scripts_start')
    <script src="{{ asset('modules/CreditDebitNotes/Resources/assets/js/credit_notes/settings.min.js?v=' . module_version('credit-debit-notes')) }}"></script>
@endpush
