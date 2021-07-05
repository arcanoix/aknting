@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('payroll::general.pay_calendars', 1)]))

@section('content')
    {!! Form::open([
        'id' => 'pay-calendar',
        'route' => 'payroll.pay-calendars.store',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true
    ]) !!}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'font') }}

                    {{ Form::selectGroup('type', trans_choice('general.types', 1), 'bars', $types, '', ['required' => 'required', 'change' => 'onChangeType', 'sort-options' => 'false']) }}

                    @stack('pay_day_mode_input_start')
                    <akaunting-select
                        class="col-md-6 required d-none"
                        :class="[{'show': field.pay_day_mode}]"
                        :form-classes="[{'has-error': form.errors.get('pay_day_mode') }]"
                        :icon="'bars'"
                        :title="'{{ trans('payroll::pay-calendars.pay_day_mode') }}'"
                        :placeholder="'{{ trans('general.form.select.field', ['field' => trans('payroll::pay-calendars.pay_day_mode')]) }}'"
                        :name="'pay_day_mode'"
                        :options="[]"
                        :dynamic-options="options.pay_day_modes"
                        :value="'{{ old('pay_day_mode') }}'"
                        @interface="form.pay_day_mode = $event"
                        @change="onChangePayDayMode($event)"
                        :form-error="form.errors.get('pay_day_mode')"
                        :no-data-text="'{{ trans('general.no_data') }}'"
                        :no-matching-data-text="'{{ trans('general.no_matching_data') }}'"
                        :sort-options="false"
                    ></akaunting-select>
                    @stack('pay_day_mode_input_end')

                    @stack('pay_day_input_start')
                    <div class="form-group col-md-6 required d-none" :class="[{'has-error': {{ 'form.errors.get("pay_day")' }} }, {'show': field.pay_day}]">
                        {!! Form::label('pay_day', trans('payroll::pay-calendars.pay_day'), ['class' => 'form-control-label'])!!}

                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            {!! Form::text('pay_day', null, [
                                'class' => 'form-control',
                                'placeholder' => trans('general.form.enter', ['field' => trans('payroll::pay-calendars.pay_day')]),
                                'v-model' => 'form.pay_day',
                            ]) !!}
                        </div>

                        <div class="invalid-feedback d-block" v-if="{{ 'form.errors.has("pay_day")' }}" v-html="{{ 'form.errors.get("pay_day")' }}"></div>
                    </div>
                    @stack('pay_day_input_end')
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="form-group col-md-12" :class="[{'has-error': {{ 'form.errors.get("employees")' }} }]">
                    {{ Form::label('run_payroll_employee', trans_choice('payroll::general.employees', 2), ['class' => 'control-label']) }}

                    <label class="input-checkbox"></label>

                    <div class="row">
                        @foreach($employees as $item)
                            <div class="col-md-3 role-list">
                                <div class="custom-control custom-checkbox">
                                    {{ Form::checkbox('employees', $item->id, null, ['id' => 'employees-' . $item->id, 'class' => 'custom-control-input', 'v-model' => 'form.employees']) }}
                                    <label class="custom-control-label" for="employees-{{ $item->id }}">
                                        {{ $item->contact->name . ' ' . $item->last_name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="invalid-feedback d-block" v-if="{{ 'form.errors.has("employees")' }}" v-html="{{ 'form.errors.get("employees")' }}"></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-footer">
                <div class="row float-right">
                    {{ Form::saveButtons('payroll.pay-calendars.index') }}
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/pay-calendars.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
