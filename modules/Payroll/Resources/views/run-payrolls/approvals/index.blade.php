@extends('layouts.admin')

@section('title',  trans('general.title.new', ['type' => trans_choice('payroll::general.wizard.approval', 1)]))

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
                            <a  href="{{ route('payroll.pay-calendars.run-payrolls.pay-slips.index', [$payCalendar->id, $runPayroll->id]) }}">
                                <button type="button"  class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                                    <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                                </button>
                                <p class="mt-2 text-muted step-text">{{ trans('payroll::general.pay_slips') }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <button href="#step-4" type="button"class="btn btn-default btn-lg wizard-steps rounded-circle">
                                <span class="btn-inner--icon wizard-steps-inner wizard-steps-color">4</span>
                            </button>
                            <p class="mt-2 text-muted step-text">{{ trans('payroll::general.approval') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! Form::open([
        'id' => 'approval_form',
        'route' => ['payroll.pay-calendars.run-payrolls.approvals.update', $payCalendar->id, $runPayroll->id],
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'approval_form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true
    ]) !!}
        <div class="card card-default">
            <div class="card-body">
                {{ Form::dateGroup('payment_date', trans('payroll::run-payrolls.payment_date'), 'calendar',['id' => 'payment_date', 'class' => 'form-control datepicker', 'required' => 'required', 'show-date-format' => company_date_format(), 'date-format' => 'Y-m-d', 'autocomplete' => 'off', 'v-model' => 'approval_form.payment_date', 'v-error' => 'approval_form.errors.get("payment_date")', 'v-error-message' => 'approval_form.errors.get("payment_date")'], $runPayroll->payment_date) }}
            </div>
        </div>

        <div class="card card-default">
            <div class="card-header with-border">
                <h3 class="mb-0">{{ trans('payroll::general.ready_approve') }}</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-md-2">{{ trans('general.name') }}</th>
                            <th class="col-md-2">{{ trans_choice('employees::general.positions', 1) }}</th>
                            <th class="col-md-2">{{ trans_choice('payroll::general.salaries', 1) }}</th>
                            <th class="col-md-2">{{ trans_choice('payroll::general.benefits', 1) }}</th>
                            <th class="col-md-2">{{ trans_choice('payroll::general.deductions', 1) }}</th>
                            <th class="col-md-2">{{ trans('payroll::general.total', ['type' => trans('general.amount')]) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-md-2 border-0"><a href="{{ url('payroll/employees/' . $item->id ) }}">{{ $item->employee->name  }}</a></td>
                                <td class="col-md-2 border-0">{{ $item->employee->position->name  }}</td>
                                <td class="col-md-2 border-0">@money($item->salary, $runPayroll->currency_code, true)</td>
                                <td class="col-md-2 border-0">@money($item->benefit, $runPayroll->currency_code, true)</td>
                                <td class="col-md-2 border-0">@money($item->deduction, $runPayroll->currency_code, true)</td>
                                <td class="col-md-2 border-0">@money($item->total, $runPayroll->currency_code, true)</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        {!! Form::button('<span class="fa fa-check"></span> &nbsp;' . trans('payroll::general.approve'), ['type' => 'submit', 'class' => 'btn btn-success  button-submit', 'data-loading-text' => trans('general.loading')]) !!}
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/run-payrolls.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
