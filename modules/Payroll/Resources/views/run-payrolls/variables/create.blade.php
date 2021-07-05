@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans('payroll::general.wizard.variables')]))

@section('content')
    <payroll-run-payroll
        :steps="{{ json_endode($steps) }}">
    </payroll-run-payroll>

    <div class="card card-solid">
        <div class="card-header wizard-header pb-0">
            <div class="container-fluid">
                <div class="row">
                    <hr class="wizard-line">
                    <div class="col-md-3">
                        <div class="text-center">
                            <a href="{{ route('payroll.run-payrolls.edit', $run_payroll->id) }}">
                                <button type="button" class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                                    <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                                </button>
                                <p class="mt-2 text-muted step-text">{{ trans_choice('payroll::general.employees', 2) }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <button href="#step-2" type="button" class="btn btn-default btn-lg wizard-steps rounded-circle">
                                <span class="btn-inner--icon wizard-steps-inner wizard-steps-color">2</span>
                            </button>
                            <p class="mt-2 text-muted step-text">{{ trans('payroll::general.variables') }}</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary btn-lg wizard-steps rounded-circle steps">
                                <span class="btn-inner--icon wizard-steps-inner wizard-steps-color">3</span>
                            </button>
                            <p class="mt-2 text-muted step-text">{{ trans('payroll::general.pay_slips') }}</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary btn-lg wizard-steps rounded-circle steps">
                                <span class="btn-inner--icon wizard-steps-inner wizard-steps-color">4</span>
                            </button>
                            <p class="mt-2 text-muted step-text">{{ trans('payroll::general.approval') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card card-default">
                <div class="card-header with-border">
                    <h3 class="mb-0">{{ trans('payroll::general.employee_profile_information') }}</h3>
                </div>

                <div class="card-body">
                    <akaunting-select
                        class="col-md-12"
                        :title="'{{ trans_choice('payroll::general.employees', 1) }}'"
                        :placeholder="'{{ trans('general.form.select.field', ['field' => trans_choice('payroll::general.employees', 1)]) }}'"
                        :name="'employee'"
                        :options="{{ json_encode($employees) }}"
                        :value="'{{ old('employee') }}'"
                        :icon="'exchange'"
                        @interface="employee = $event"
                        {{-- chance --}}
                    ></akaunting-select>

                    <input type="hidden" id="paycalendar_id" name="paycalendar_id" value="{{ $payCalendar->id }}">

                    <div class="form-group col-md-12">
                        <div class="card-header border-bottom-1 show-transaction-card-header">
                            <b class="text-sm font-weight-600">{{ trans_choice('payroll::general.salaries', 1) }}</b> <a class="float-right text-xs">@money(0, $run_payroll->currency_code, true)</a>
                        </div>

                        <div class="card-header border-bottom-1 show-transaction-card-header">
                            <b class="text-sm font-weight-600">{{ trans_choice('payroll::general.benefits', 2) }}</b> <a class="float-right text-xs">@money(0, $run_payroll->currency_code, true)</a>
                        </div>

                        <div class="card-header border-bottom-1 show-transaction-card-header">
                            <b class="text-sm font-weight-600">{{ trans_choice('payroll::general.deductions', 2) }}</b> <a class="float-right text-xs">@money(0, $run_payroll->currency_code, true)</a>
                        </div>

                        <div class="card-header border-bottom- show-transaction-card-header">
                            <b class="text-sm font-weight-600">{{ trans('payroll::general.total', ['type' => trans('general.amount')]) }}</b> <a class="float-right text-xs">@money(0, $run_payroll->currency_code, true)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header with-border">
                    <h3 class="mb-0">{{ trans_choice('payroll::general.benefits', 1) }}</h3>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="benefits">
                        <thead class="thead-light">
                            <tr class="row table-head-line">
                                @stack('actions_th_start')
                                    <th class="col-md-2 action-column border-right-0 border-bottom-0">{{ trans('general.actions') }}</th>
                                @stack('actions_th_end')

                                @stack('name_th_start')
                                    <th class="col-md-7 text-left border-right-0 border-bottom-0">{{ trans_choice('general.types', 1) }}</th>
                                @stack('name_th_end')

                                @stack('total_th_start')
                                    <th class="col-md-3 text-right border-left-0 border-bottom-0">{{ trans('general.amount') }}</th>
                                @stack('total_th_end')
                            </tr>
                        </thead>

                        <tbody>
                            @include('payroll::partials.employee.benefit.item')

                            <tr class="row align-items-center border-top-1" id="addBenefit">
                                <td class="col-md-2 border-0 hidden-xs text-center">
                                    <button type="button" @click="onBenefit"
                                            id="button-add-item"
                                            data-toggle="tooltip"
                                            title="{{ trans('general.add') }}"
                                            class="btn btn-icon btn-outline-success btn-lg"
                                            data-original-title="{{ trans('general.add') }}">
                                            <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                                <td class="col-md-7 border-0 hidden-xs text-left"></td>
                                <td class="col-md-3 border-0 hidden-xs text-right"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header with-border">
                    <h3 class="mb-0">{{ trans_choice('payroll::general.deductions', 1) }}</h3>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="benefits">
                        <thead class="thead-light">
                            <tr class="row table-head-line">
                                @stack('actions_th_start')
                                    <th class="col-md-2 action-column border-right-0 border-bottom-0">{{ trans('general.actions') }}</th>
                                @stack('actions_th_end')

                                @stack('name_th_start')
                                    <th class="col-md-7 text-left border-right-0 border-bottom-0">{{ trans_choice('general.types', 1) }}</th>
                                @stack('name_th_end')

                                @stack('total_th_start')
                                    <th class="col-md-3 text-right border-left-0 border-bottom-0">{{ trans('general.amount') }}</th>
                                @stack('total_th_end')
                            </tr>
                        </thead>

                        <tbody>
                            @include('payroll::partials.employee.deduction.item')

                            @stack('add_item_td_start')
                                <tr  class="row align-items-center border-top-1" id="addDeduction">
                                    <td class="col-md-2 border-0 hidden-xs text-center"><button type="button" @click="onDeduction" id="button-add-item" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-icon btn-outline-success btn-lg" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                                    <td class="col-md-7 border-0 hidden-xs text-left"></td>
                                    <td class="col-md-3 border-0 hidden-xs text-right"></td>
                                </tr>
                            @stack('add_item_td_end')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-right">
                    <a href="{{ url('payroll/pay-calendars/'. $run_payroll->pay_calendar_id .'/'.'run-payrolls/'. $run_payroll->id . '/pay-slips') }}" id="wizard-skip" class="btn btn-white header-button-top"><span class="fa fa-share"></span> &nbsp;{{ trans('general.skip') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var option_items = false;
    </script>

    <script src="{{ asset('modules/Payroll/Resources/assets/js/run-payrolls.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
