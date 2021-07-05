{!! Form::open([
    'id' => 'run-payroll',
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
            {{ Form::dateGroup('payment_date', trans('payroll::run-payrolls.payment_date'), 'calendar', ['id' => 'payment_date', 'class' => 'form-control datepicker', 'required' => 'required', 'show-date-format' => company_date_format(), 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], $runPayroll->payment_date) }}
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
                        <th class="col-md-2 text-right">{{ trans_choice('payroll::general.salaries', 1) }}</th>
                        <th class="col-md-2 text-right">{{ trans_choice('payroll::general.benefits', 1) }}</th>
                        <th class="col-md-2 text-right">{{ trans_choice('payroll::general.deductions', 1) }}</th>
                        <th class="col-md-2 text-right">{{ trans('payroll::general.total', ['type' => trans('general.amount')]) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $item)
                    <tr class="row align-items-center border-top-1">
                            <td class="col-md-2 border-0"><a href="{{ route('employees.employees.show', $item->id ) }}">{{ $item->employee->contact->name }}</a></td>
                            <td class="col-md-2 border-0">{{ $item->employee->position->name }}</td>
                            <td class="col-md-2 border-0 text-right">@money($item->salary, $runPayroll->currency_code, true)</td>
                            <td class="col-md-2 border-0 text-right">@money($item->benefit, $runPayroll->currency_code, true)</td>
                            <td class="col-md-2 border-0 text-right">@money($item->deduction, $runPayroll->currency_code, true)</td>
                            <td class="col-md-2 border-0 text-right">@money($item->total, $runPayroll->currency_code, true)</td>
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
                    {!! Form::button(
                        '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-check"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text">' . trans('payroll::general.approve') . '</span>',
                        [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success button-submit header-button-top', 'data-loading-text' => trans('general.loading')]) !!}
                </div>
            </div>
        </div>
    </div>
{!! Form::close() !!}
