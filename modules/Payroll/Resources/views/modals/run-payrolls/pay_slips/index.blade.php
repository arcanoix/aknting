{!! Form::open([
    'id' => 'run-payroll',
    'route' => ['payroll.pay-calendars.run-payrolls.pay-slips.post', $payCalendar->id, $runPayroll->id],
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="card">
        <div class="card-header with-border">
            <h3 class="mb-0">{{ trans('payroll::general.pay_slip_title') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <akaunting-select
                    class="col-md-10"
                    :title="'{{ trans_choice('payroll::general.employees', 1) }}'"
                    :placeholder="'{{ trans('general.form.select.field', ['field' => trans_choice('payroll::general.employees', 1)]) }}'"
                    :name="'employee'"
                    :options="{{ json_encode($employees) }}"
                    :value="'{{ old('employee') }}'"
                    :icon="'exchange'"
                    @interface="form.employee = $event"
                    @change="onChangePaySlipEmployee"
                ></akaunting-select>

                <input type="hidden" id="pay_calendar_id" name="pay_calendar_id" value="{{ $payCalendar->id }}">
                <input type="hidden" id="run_payroll_id" name="run_payroll_id" value="{{ $runPayroll->id }}">

                <div class="form-group col-md-2">
                    <label class="control-label print-button" style="margin-top: 1.4rem"></label>
                    <div class="input-group">
                        <button type="button" @click="onPrintPaySlipEmployee" :disabled="!form.employee" class="btn btn-default btn-block employee-print"><span class="fa fa-print"></span> Print PaySlip </button>
                    </div>
                </div>
            </div>

            <div class="table table-responsive">
                <table class="table table-striped table-hover">
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
                            <td class="col-md-3" id="employee-payment-date" v-html="pay_slips.employee.payment_date">-</td>
                            <td class="col-md-3" id="employee-tax-number" v-html="pay_slips.employee.tax_number">-</td>
                            <td class="col-md-3" id="employee-bank-account" v-html="pay_slips.employee.bank_account_number">-</td>
                            <td class="col-md-3" id="employee-payment-methods" v-html="pay_slips.employee.payment_method">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="table table-responsive">
                <table class="table table-striped table-hover">
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
                            <td class="col-md-3" id="employee-position" v-html="pay_slips.employee.position">-</td>
                            <td class="col-md-3" id="employee-from-date" v-html="pay_slips.employee.from_date">-</td>
                            <td class="col-md-3" id="employee-to-date" v-html="pay_slips.employee.to_date">-</td>
                            <td class="col-md-3"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-12" colspan="5">{{ trans_choice('payroll::general.benefits', 2) }}</th>
                                </tr>
                            </thead>

                            <tbody v-if="pay_slips.employee.salary">
                                <tr>
                                    <td class="col-md-10 text-left" colspan="4">{{ trans_choice('payroll::general.salaries', 1) }}</td>
                                    <td class="col-md-2 text-right" v-html="pay_slips.employee.salary">-</td>
                                </tr>

                                <tr v-for="benefit in pay_slips.employee.benefits">
                                    <td class="col-md-10 text-left" colspan="4" v-html="benefit.name">-</td>
                                    <td class="col-md-2 text-right" v-html="benefit.amount">-</td>
                                </tr>
                            </tbody>

                            <tbody v-else>
                                <tr>
                                    <td class="col-md-10 text-left" colspan="4">-</td>
                                    <td class="col-md-2 text-right">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-12" colspan="5">{{ trans_choice('payroll::general.deductions', 2) }}</th>
                                </tr>
                            </thead>
                        </thead>

                        <tbody v-if="pay_slips.employee.deductions.length">
                            <tr v-for="deduction in pay_slips.employee.deductions">
                                <td class="col-md-10 text-left" colspan="4" v-html="deduction.name">-</td>
                                <td class="col-md-2 text-right" v-html="deduction.amount">-</td>
                            </tr>
                        </tbody>

                        <tbody v-else>
                            <tr>
                                <td class="col-md-10 text-left" colspan="4">-</td>
                                <td class="col-md-2 text-right">-</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-10 text-right">{{ trans_choice('general.totals', 2) }}</th>
                                    <th class="col-md-2 text-right" v-html="pay_slips.employee.total">-</th>
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
                    {!! Form::button(
                        '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-share"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text">' . trans('payroll::general.next') . '</span>',
                        [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success button-submit header-button-top', 'data-loading-text' => trans('general.loading')]) !!}
                </div>
            </div>
        </div>
    </div>
{!! Form::close() !!}
