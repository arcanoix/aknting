@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('employees::general.employees', 1)]))

@section('content')
    {!! Form::model($employee, [
        'id' => 'edit_employee',
        'method' => 'PATCH',
        'route' => ['employees.employees.update', $employee->id],
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
            <h3 class="mb-0">{{ trans('employees::employees.personal_information') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('name', trans('general.name'), 'font', ['required' => 'required', 'autofocus' => 'autofocus'], $employee->contact->name) }}

                {{ Form::textGroup('email', trans('general.email'), 'envelope', [], $employee->contact->email) }}

                {{ Form::dateGroup('birth_day', trans('employees::employees.birth_day'), 'calendar', ['id' => 'birth_day', 'class' => 'form-control datepicker', 'required' => 'required', 'show-date-format' => company_date_format(), 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::parse($employee->birth_day)->toDateString()) }}

                {{ Form::selectGroup('gender', trans('employees::employees.gender'), 'fas fa-transgender-alt', $genders, $employee->gender) }}

                {{ Form::textGroup('phone', trans('general.phone'), 'phone', [], $employee->contact->phone) }}

                {{ Form::selectAddNewGroup('position_id', trans_choice('employees::general.positions', 1), 'folder-open', $positions, $employee->position_id, [ 'required' => 'required', 'path' => route('employees.modals.positions.create'), 'field' => ['key' => 'id', 'value' => 'name']]) }}

                {{ Form::textareaGroup('address', trans('general.address'), '', $employee->contact->address) }}

                {{ Form::radioGroup('enabled', trans('general.enabled'), $employee->contact->enabled) }}

                @stack('create_user_input_start')
                <div id="employee-create-user" class="form-group col-md-12 margin-top">
                    <div class="custom-control custom-checkbox">
                        @if ($employee->contact->user_id)
                            {{ Form::checkbox('create_user', '1', 1, [
                                'id' => 'create_user',
                                'class' => 'custom-control-input',
                                'disabled' => 'true'
                            ]) }}

                            <label class="custom-control-label" for="create_user">
                                <strong>{{ trans('customers.user_created') }}</strong>
                            </label>
                        @else
                            {{ Form::checkbox('create_user', '1', null, [
                                'id' => 'create_user',
                                'class' => 'custom-control-input',
                                'v-on:input' => 'onCanLogin($event)'
                            ]) }}

                            <label class="custom-control-label" for="create_user">
                                <strong>{{ trans('customers.can_login') }}</strong>
                            </label>
                        @endif
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
            <h3 class="mb-0">{{ trans('employees::employees.salary') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['currency' => $currency, 'dynamic-currency' => 'currency'], $employee->amount) }}

                {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, $employee->contact->currency_code, ['required' => 'required', 'change' => 'onChangeCurrency']) }}

                {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', [], $employee->contact->tax_number) }}

                {{ Form::textGroup('bank_account_number', trans('employees::employees.bank_account_number'), 'pencil-alt', [], $employee->bank_account_number) }}

                {{ Form::dateGroup('hired_at', trans('employees::employees.hired_at'), 'calendar', ['id' => 'hired_at', 'class' => 'form-control datepicker', 'required' => 'required', 'show-date-format' => company_date_format(), 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::parse($employee->hired_at)->toDateString()) }}
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
                    $employee->attachment,
                    'col-md-12'
                ) }}
            </div>
        </div>
    </div>
    @stack('card_attachments_end')

    @can('update-employees-employees')
        <div class="card">
            <div class="card-footer">
                <div class="row float-right">
                    {{ Form::saveButtons('employees.employees.index') }}
                </div>
            </div>
        </div>
    @endcan

    {!!Form::close() !!}
@endsection

@push('scripts_start')
    <script>
        var can_login_errors = {
            valid: '{!! trans('validation.required', ['attribute' => 'email']) !!}',
            email: '{{ trans('customers.error.email') }}'
        };
    </script>

    <script
        src="{{ asset('modules/Employees/Resources/assets/js/employees/edit.min.js?v=' . module_version('employees')) }}"></script>
@endpush
