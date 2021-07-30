@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('employees::general.employees', 1)]))

@section('content')
    {!! Form::open([
        'id' => 'create_employee',
        'route' => 'employees.employees.store',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true
    ]) !!}

    @stack('card_personal_information_start')
    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ trans('employees::employees.personal_information') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('name', trans('general.name'), 'font', ['required' => 'required', 'autofocus' => 'autofocus']) }}

                {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

                {{ Form::dateGroup('birth_day', trans('employees::employees.birth_day'), 'calendar', ['id' => 'birth_day', 'class' => 'form-control datepicker', 'required' => 'required', 'show-date-format' => company_date_format(), 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::now()->toDateString()) }}

                {{ Form::selectGroup('gender', trans('employees::employees.gender'), 'fas fa-transgender-alt', $genders) }}

                {{ Form::textGroup('phone', trans('general.phone'), 'phone', []) }}

                {{ Form::selectAddNewGroup('position_id', trans_choice('employees::general.positions', 1), 'folder-open', $positions, '', [ 'required' => 'required', 'path' => route('employees.modals.positions.create'), 'field' => ['key' => 'id', 'value' => 'name']]) }}

                {{ Form::textareaGroup('address', trans('general.address')) }}

                {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}

                @stack('create_user_input_start')
                <div id="employee-create-user" class="form-group col-md-12 margin-top">
                    <div class="custom-control custom-checkbox">
                        {{ Form::checkbox('create_user', '1', null, [
                            'v-model' => 'form.create_user',
                            'id' => 'create_user',
                            'class' => 'custom-control-input',
                            '@input' => 'onCanLogin($event)'
                        ]) }}

                        <label class="custom-control-label" for="create_user">
                            <strong>{{ trans('customers.can_login') }}</strong>
                        </label>
                    </div>
                </div>
                @stack('create_user_input_end')

                <div v-if="can_login" class="row col-md-12">
                    {{Form::passwordGroup('password', trans('auth.password.current'), 'key', [], 'col-md-6 password')}}

                    {{Form::passwordGroup('password_confirmation', trans('auth.password.current_confirm'), 'key', [], 'col-md-6 password')}}
                </div>

                {{ Form::hidden('type', 'employee') }}
            </div>
        </div>
    </div>
    @stack('card_personal_information_end')

    @stack('card_salary_start')
    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ trans('employees::employees.salary') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'currency' => $currency, 'dynamic-currency' => 'currency'], 0) }}

                {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, setting('default.currency'), ['required' => 'required', 'change' => 'onChangeCurrency']) }}

                {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

                {{ Form::textGroup('bank_account_number', trans('employees::employees.bank_account_number'), 'pencil-alt', []) }}

                {{ Form::dateGroup('hired_at', trans('employees::employees.hired_at'), 'calendar', ['id' => 'hired_at', 'class' => 'form-control datepicker', 'required' => 'required', 'show-date-format' => company_date_format(), 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::now()->toDateString()) }}
            </div>
        </div>
    </div>
    @stack('card_salary_end')

    @stack('card_attachments_start')
    <div class="card">
        <div class="card-body">
            <div class="row">
                {{ Form::fileGroup(
                    'attachment',
                    trans('general.attachment'),
                    '',
                    [
                        'dropzone-class' => 'w-100',
                        'multiple' => 'multiple',
                        'options' => ['acceptedFiles' => $file_types]
                    ],
                    null,
                    'col-md-12'
                ) }}
            </div>
        </div>
    </div>
    @stack('card_attachments_end')

    <div class="card">
        <div class="card-footer">
            <div class="row float-right">
                {{ Form::saveButtons('employees.employees.index') }}
            </div>
        </div>
    </div>

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script>
        var can_login_errors = {
            valid: '{!! trans('validation.required', ['attribute' => 'email']) !!}',
            email: '{{ trans('customers.error.email') }}'
        };
    </script>

    <script
        src="{{ asset('modules/Employees/Resources/assets/js/employees/create.min.js?v=' . module_version('employees')) }}"></script>
@endpush
