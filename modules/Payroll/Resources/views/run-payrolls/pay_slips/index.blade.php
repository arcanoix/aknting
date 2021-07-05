@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans('payroll::general.wizard.payslips')]))

@section('content')
    <div class="card card-solid">
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <hr class="wizard-line">
                    <div class="col-md-3">
                        <div class="text-center">
                            <a href="{{ route('payroll.run-payrolls.edit', [$runPayroll->id]) }}">
                                <button type="button" class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                                    <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                                </button>
                                <p class="mt-2 text-muted step-text">{{ trans_choice('payroll::general.employees', 2) }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <a href="{{ route('payroll.run-payrolls.variables.edit', [$runPayroll->id]) }}">
                                <button type="button"  class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                                    <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                                </button>
                                <p class="mt-2 text-muted step-text">{{ trans('payroll::general.variables') }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <button  href="#step-3" type="button" class="btn btn-default btn-lg wizard-steps rounded-circle">
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

    <div class="card">
        <div class="card-header with-border">
            <h3 class="mb-0">{{ trans('payroll::general.pay_slip_title') }}</h3>
        </div>

        <div class="card-body">
            <akaunting-select
                class="col-md-10"
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
            <input type="hidden" id="run_payroll_id" name="run_payroll_id" value="{{ $runPayroll->id }}">

            <div class="form-group col-md-2">
                <label class="control-label print-button"></label>
                <div class="input-group">
                    <a href="#" class="btn btn-default btn-block employee-print"><span class="fa fa-print"></span> Print PaySlip </a>
                </div>
            </div>

            <div class="table table-responsive">
                <table class="table table-striped table-hover" id="tbl-taxes">
                    <thead>
                        <tr>
                            <th class="col-md-3">{{ trans('payroll::run-payrolls.payment_date') }}</th>
                            <th class="col-md-3">{{ trans('general.tax_number') }}</th>
                            <th class="col-md-3">{{ trans('employees::employees.bank_account_number') }}</th>
                            <th class="col-md-3">{{ trans_choice('general.payment_methods', 1) }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="col-md-3" id="employee-payment-date">-</td>
                            <td class="col-md-3" id="employee-tax-number">-</td>
                            <td class="col-md-3" id="employee-bank-account">-</td>
                            <td class="col-md-3" id="employee-payment-methods">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="table table-responsive">
                <table class="table table-striped table-hover" id="tbl-taxes">
                    <thead>
                        <tr>
                            <th class="col-md-3">{{ trans_choice('employees::general.positions', 1) }}</th>
                            <th class="col-md-3">{{ trans('payroll::run-payrolls.from_date') }}</th>
                            <th class="col-md-3">{{ trans('payroll::run-payrolls.to_date') }}</th>
                            <th class="col-md-3"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="col-md-3" id="employee-position">-</td>
                            <td class="col-md-3" id="employee-from-date">-</td>
                            <td class="col-md-3" id="employee-to-date">-</td>
                            <td class="col-md-3"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover" id="tbl-benefits">
                            <thead>
                                <tr>
                                    <th class="col-md-12" colspan="5">{{ trans_choice('payroll::general.benefits', 2) }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="col-md-10 text-left" colspan="4">-</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover" id="tbl-deductions">
                            <thead>
                                <tr>
                                    <th class="col-md-12" colspan="5">{{ trans_choice('payroll::general.deductions', 2) }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="col-md-10 text-left" colspan="4">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover" id="tbl-taxes">
                            <thead>
                                <tr>
                                    <th class="col-md-6 text-right"></th>
                                    <th class="col-md-4 text-left">{{ trans_choice('general.totals', 2) }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-right">
                    <a href="{{ route('payroll.pay-calendars.run-payrolls.approvals.edit', [$payCalendar->id, $runPayroll->id]) }}" id="wizard-skip" class="btn btn-success"><span class="fa fa-share"></span> &nbsp;{{ trans('payroll::general.next') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/run-payrolls.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
