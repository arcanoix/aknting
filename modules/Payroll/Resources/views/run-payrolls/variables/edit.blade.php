@extends('layouts.admin')

@section('title', trans('payroll::general.wizard.variables'))

@section('content')
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

                    <input type="hidden" id="paycalendar_id" name="paycalendar_id" value="{{ $run_payroll->pay_calendar_id }}">

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

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="benefits">
                            <thead>
                                <tr style="background-color: #f9f9f9;">
                                    @stack('actions_th_start')
                                        <th width="5%" class="text-center">{{ trans('general.actions') }}</th>
                                    @stack('actions_th_end')

                                    @stack('name_th_start')
                                        <th width="40%" class="text-left">{{ trans_choice('general.types', 1) }}</th>
                                    @stack('name_th_end')

                                    @stack('total_th_start')
                                        <th width="10%" class="text-right">{{ trans('general.amount') }}</th>
                                    @stack('total_th_end')
                                </tr>
                            </thead>

                            <tbody>
                                @php $benefit_row = 0; @endphp
                                    @if(old('benefit'))
                                        @foreach(old('benefit') as $old_benefit)
                                            @php $benefit = (object) $old_benefit; @endphp
                                                @include('payroll::partials.employee.benefit.item')
                                            @php $benefit_row++; @endphp
                                        @endforeach
                                    @endif
                                @php $benefit_row++; @endphp

                                @stack('add_item_td_start')
                                    <tr id="addBenefit">
                                        <td class="text-center"><button type="button" id="button-add-benefit" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-xs btn-primary" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                                        <td class="text-right" colspan="2"></td>
                                    </tr>
                                @stack('add_item_td_end')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header with-border">
                    <h3 class="mb-0">{{ trans_choice('payroll::general.deductions', 1) }}</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="deductions">
                            <thead>
                                <tr style="background-color: #f9f9f9;">
                                    @stack('actions_th_start')
                                        <th width="5%"  class="text-center">{{ trans('general.actions') }}</th>
                                    @stack('actions_th_end')

                                    @stack('name_th_start')
                                        <th width="40%" class="text-left">{{ trans_choice('general.types', 1) }}</th>
                                    @stack('name_th_end')

                                    @stack('total_th_start')
                                        <th width="10%" class="text-right">{{ trans('general.amount') }}</th>
                                    @stack('total_th_end')
                                </tr>
                            </thead>

                            <tbody>
                                @php $deduction_row = 0; @endphp
                                    @if(old('deduction'))
                                        @foreach(old('deduction') as $old_deduction)
                                            @php $deduction = (object) $old_deduction; @endphp
                                                @include('payroll::partials.employee.deduction.item')
                                            @php $deduction_row++; @endphp
                                        @endforeach
                                    @endif
                                @php $deduction_row++; @endphp

                                @stack('add_item_td_start')
                                    <tr id="addDeduction">
                                        <td class="text-center"><button type="button" id="button-add-deduction" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-xs btn-primary" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                                        <td class="text-right" colspan="2"></td>
                                    </tr>
                                @stack('add_item_td_end')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-right">
                    <a href="{{ route('payroll.pay-calendars.run-payrolls.pay-slips.index', [$run_payroll->pay_calendar_id, $run_payroll->id]) }}" id="wizard-skip" class="btn btn-success"><span class="fa fa-share"></span> &nbsp;{{ trans('payroll::general.next') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/run-payrolls.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
