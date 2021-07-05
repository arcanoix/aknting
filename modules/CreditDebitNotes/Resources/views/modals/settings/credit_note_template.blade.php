<div class="modal-body pb-0">
    {!! Form::open([
            'route' => 'credit-debit-notes.modals.credit-note-templates.update',
            'method' => 'PATCH',
            'id' => 'credit_note_template',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'formCreditNoteTemplate.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button mb-0',
            'novalidate' => true
    ]) !!}
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="bg-print border-radius-default print-edge choose" @click="formCreditNoteTemplate.template='default'">
{{--                    TODO: change thumbnails to original--}}
                    <img src="{{ asset('modules/CreditDebitNotes/Resources/assets/img/credit_note_templates/default.png?v=' . module_version('credit-debit-notes')) }}" class="mb-1 mt-3" height="200" alt="Default"/>
                    <label>
                        <input type="radio" name="template" value="default" v-model="formCreditNoteTemplate.template">
                        {{ trans('credit-debit-notes::settings.credit_note.default') }}
                    </label>
                </div>
            </div>

            <div class="col-md-4 text-center px-2">
                <div class="bg-print border-radius-default print-edge choose" @click="formCreditNoteTemplate.template='classic'">
                    <img src="{{ asset('modules/CreditDebitNotes/Resources/assets/img/credit_note_templates/classic.png?v=' . module_version('credit-debit-notes')) }}" class="mb-1 mt-3" height="200" alt="Classic"/>
                    <label>
                        <input type="radio" name="template" value="classic" v-model="formCreditNoteTemplate.template">
                        {{ trans('credit-debit-notes::settings.credit_note.classic') }}
                    </label>
                </div>
            </div>

            <div class="col-md-4 text-center px-0">
                <div class="bg-print border-radius-default print-edge choose" @click="formCreditNoteTemplate.template='modern'">
                    <img src="{{ asset('modules/CreditDebitNotes/Resources/assets/img/credit_note_templates/modern.png?v=' . module_version('credit-debit-notes')) }}" class="mb-1 mt-3" height="200" alt="Modern"/>
                    <label>
                        <input type="radio" name="template" value="modern" v-model="formCreditNoteTemplate.template">
                        {{ trans('credit-debit-notes::settings.credit_note.modern') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            @stack('color_input_start')
                <div class="form-group col-md-12 {{ $errors->has('color') ? 'has-error' : ''}}">
                    {!! Form::label('color', trans('general.color'), ['class' => 'form-control-label']) !!}
                    <div class="input-group input-group-merge" id="credit-note-color-picker">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <el-color-picker popper-class="template-color-picker" v-model="formCreditNoteTemplate.color" size="mini" :predefine="predefineColors" @change="onChangeColor"></el-color-picker>
                            </span>
                        </div>
                        {!! Form::text('color', setting('credit_note.color'), ['v-model' => 'formCreditNoteTemplate.color', 'id' => 'color', 'class' => 'form-control color-hex', 'required' => 'required']) !!}
                    </div>
                    {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
                </div>
            @stack('color_input_end')
        </div>

        {!! Form::hidden('_template', setting('credit-debit-notes.credit_note.template')) !!}
        {!! Form::hidden('_prefix', 'credit-debit-notes.credit_note') !!}
    {!! Form::close() !!}
</div>
