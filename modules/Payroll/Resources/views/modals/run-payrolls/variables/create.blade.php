{!! Form::open([
    'id' => 'run-payroll',
    'route' => ['payroll.pay-calendars.run-payrolls.variables.store', $run_payroll->pay_calendar_id, $run_payroll->id],
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
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
                        @interface="form.employee = $event"
                        @change="onChangeEmployee"
                    ></akaunting-select>

                    <input type="hidden" id="paycalendar_id" name="paycalendar_id" value="{{ $payCalendar->id }}">
                    <input type="hidden" id="run_payroll_id" name="run_payroll_id" value="{{ $run_payroll->id }}">

                    <div class="form-group col-md-12">
                        <div class="card-header border-bottom-1 show-transaction-card-header">
                            <b class="text-sm font-weight-600">{{ trans_choice('payroll::general.salaries', 1) }}</b>
                            <a class="float-right text-xs" v-html="variables.employee.salary">@money(0, $run_payroll->currency_code, true)</a>
                        </div>

                        <div class="card-header border-bottom-1 show-transaction-card-header">
                            <b class="text-sm font-weight-600">{{ trans_choice('payroll::general.benefits', 2) }}</b>
                            <a class="float-right text-xs" v-html="variables.employee.benefits">@money(0, $run_payroll->currency_code, true)</a>
                        </div>

                        <div class="card-header border-bottom-1 show-transaction-card-header">
                            <b class="text-sm font-weight-600">{{ trans_choice('payroll::general.deductions', 2) }}</b>
                            <a class="float-right text-xs" v-html="variables.employee.deductions">@money(0, $run_payroll->currency_code, true)</a>
                        </div>

                        <div class="card-header border-bottom- show-transaction-card-header">
                            <b class="text-sm font-weight-600">{{ trans('payroll::general.total', ['type' => trans('general.amount')]) }}</b>
                            <a class="float-right text-xs" v-html="variables.employee.total">@money(0, $run_payroll->currency_code, true)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="mb-0">{{ trans_choice('payroll::general.benefits', 1) }}</h3>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="benefits">
                        <thead class="thead-light">
                            <tr class="row table-head-line">
                                @stack('actions_th_start')
                                    <th class="col-md-2 action-column border-top-0 border-left-0 border-right-0 border-bottom-0">{{ trans('general.actions') }}</th>
                                @stack('actions_th_end')

                                @stack('name_th_start')
                                    <th class="col-md-7 text-left border-top-0 border-right-0 border-bottom-0">{{ trans_choice('general.types', 1) }}</th>
                                @stack('name_th_end')

                                @stack('total_th_start')
                                    <th class="col-md-3 text-right border-top-0 border-right-0 border-bottom-0">{{ trans('general.amount') }}</th>
                                @stack('total_th_end')
                            </tr>
                        </thead>

                        <tbody>
                            @include('payroll::partials.employee.benefit.item')

                            @stack('add_item_td_start')

                            <tr class="row border-top-1" id="addBenefit">
                                <td class="col-md-2 border-0 hidden-xs text-center">
                                    <button type="button"
                                        :disabled="!this.form.employee"
                                        id="button-add-benefit-item"
                                        data-toggle="tooltip"
                                        title="{{ trans('general.add') }}"
                                        class="btn btn-icon btn-outline-success btn-lg"
                                        data-original-title="{{ trans('general.add') }}"
                                        @click="addBenefit">
                                            <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                                <td class="col-md-7 border-top-0 border-right-0 border-bottom-0 hidden-xs text-left"></td>
                                <td class="col-md-3 border-0 hidden-xs text-right"></td>
                            </tr>

                            @stack('add_item_td_end')
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">
                    <h3 class="mb-0">{{ trans_choice('payroll::general.deductions', 1) }}</h3>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="deductions">
                        <thead class="thead-light">
                            <tr class="row table-head-line">
                                @stack('actions_th_start')
                                    <th class="col-md-2 action-column border-top-0 border-left-0 border-right-0 border-bottom-0">{{ trans('general.actions') }}</th>
                                @stack('actions_th_end')

                                @stack('name_th_start')
                                    <th class="col-md-7 text-left border-top-0 border-right-0 border-bottom-0">{{ trans_choice('general.types', 1) }}</th>
                                @stack('name_th_end')

                                @stack('total_th_start')
                                    <th class="col-md-3 text-right border-top-0 border-right-0 border-bottom-0">{{ trans('general.amount') }}</th>
                                @stack('total_th_end')
                            </tr>
                        </thead>

                        <tbody>
                            @include('payroll::partials.employee.deduction.item')

                            @stack('add_item_td_start')

                            <tr class="row border-top-1" id="addDeduction">
                                <td class="col-md-2 border-0 hidden-xs text-center">
                                    <button type="button"
                                        :disabled="!this.form.employee"
                                        id="button-add-deduction-item"
                                        data-toggle="tooltip"
                                        title="{{ trans('general.add') }}"
                                        class="btn btn-icon btn-outline-success btn-lg"
                                        data-original-title="{{ trans('general.add') }}"
                                        @click="addDeduction">
                                            <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                                <td class="col-md-7 border-top-0 border-right-0 border-bottom-0 hidden-xs text-left"></td>
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
                    {!! Form::button(
                        '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-share"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text">' . trans('payroll::general.next') . '</span>',
                        [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success button-submit header-button-top', 'data-loading-text' => trans('general.loading')]) !!}
                </div>
            </div>
        </div>
    </div>
{!! Form::close() !!}
