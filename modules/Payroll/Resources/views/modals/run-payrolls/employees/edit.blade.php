{!! Form::open([
    'id' => 'run-payroll',
    'route' => ['payroll.run-payrolls.employees.update', $run_payroll->id],
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
                {{ Form::dateGroup('from_date', trans('payroll::run-payrolls.from_date'), 'calendar', ['id' => 'from_date', 'required' => 'required', 'show-date-format' => company_date_format(), 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], $run_payroll->from_date, 'col-md-4') }}

                {{ Form::dateGroup('to_date', trans('payroll::run-payrolls.to_date'), 'calendar', ['id' => 'to_date', 'required' => 'required', 'show-date-format' => company_date_format(), 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off', 'min-date' => 'form.from_date'], $run_payroll->to_date, 'col-md-4') }}

                {{ Form::dateGroup('payment_date', trans('payroll::run-payrolls.payment_date'), 'calendar', ['id' => 'payment_date', 'required' => 'required', 'show-date-format' => company_date_format(), 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], $run_payroll->payment_date, 'col-md-4') }}
            </div>
        </div>
    </div>

    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                <h3 class="mb-0">{{ trans('payroll::general.advanced') }}</h3>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <div class="row">
                        {{ Form::textGroup('name', trans('general.name'), 'id-card-o', ['required' => 'required'], old('name', $run_payroll->name)) }}

                        {{ Form::selectAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, old('category_id', $run_payroll->category_id)) }}

                        {{ Form::selectGroup('account_id', trans_choice('general.accounts', 1), 'university', $accounts, old('account_id', $run_payroll->account_id)) }}

                        {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, old('payment_method', $run_payroll->payment_method), ['required' => 'required']) }}

                        <input type="hidden" name="currency_code" value="{{ $currency->code }}">
                        <input type="hidden" name="currency_rate" value="{{ $currency->rate }}">
                        <input type="hidden" name="pay_calendar_id" value="{{ $run_payroll->pay_calendar->id }}">
                        <input type="hidden" name="run_payroll_id" value="{{ $run_payroll->id }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header with-border">
            <h3 class="mb-0">{{ trans('payroll::general.active_employee') }}</h3>
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
                            <td class="col-md-2 hidden-xs  border-0"><a href="{{ route('employees.employees.show', $item->employee->id ) }}">{{ !empty($item->employee->contact) ? $item->employee->contact->name : '-' }}</a></td>
                            <td class="col-md-2 hidden-xs  border-0">{{ $item->employee->position->name }}</td>
                            <td class="col-md-2 hidden-xs  border-0 text-right">@money($item->employee->amount, $item->employee->contact->currency_code, true)</td>
                            <td class="col-md-2 hidden-xs  border-0 text-right">@money($item->employee->total_benefits, $item->employee->contact->currency_code, true)</td>
                            <td class="col-md-2 hidden-xs  border-0 text-right">@money($item->employee->total_deductions, $item->employee->contact->currency_code, true)</td>
                            <td class="col-md-2 hidden-xs  border-0 text-right">@money($item->employee->totals, $item->employee->contact->currency_code, true)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col-12 text-right">
                {!! Form::button(
                    '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-share"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text">' . trans('payroll::general.next') . '</span>',
                    [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success button-submit header-button-top', 'data-loading-text' => trans('general.loading')]) !!}
            </div>
        </div>
    </div>
{!! Form::close() !!}
