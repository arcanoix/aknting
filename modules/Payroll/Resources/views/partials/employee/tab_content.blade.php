<div class="tab-pane fade" id="payroll-content" role="tabpanel" aria-labelledby="payments-tab">
    <component v-bind:is="component"></component>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-gradient-success border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('payroll::general.total', ['type' => trans_choice('general.payments', 1)]) }}</h5>
                            <div class="dropdown-divider"></div>
                            <span class="h2 font-weight-bold mb-0 text-white">@money($totalPayment, setting('default.currency'), true)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-gradient-warning border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-uppercase text-muted mb-0 text-white">{{  trans('payroll::general.total', ['type' => trans_choice('payroll::general.benefits', 1)]) }}</h5>
                            <div class="dropdown-divider"></div>
                            <span class="h2 font-weight-bold mb-0 text-white">@money($totalBenefit, setting('default.currency'), true)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-gradient-danger border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-uppercase text-muted mb-0 text-white">{{trans('payroll::general.total', ['type' => trans_choice('payroll::general.deductions', 1)]) }}</h5>
                            <div class="dropdown-divider"></div>
                            <span class="h2 font-weight-bold mb-0 text-white">@money($totalDeduction, setting('default.currency'), true)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">{{ trans('payroll::employees.payment_histories')  }}</h3>
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <tbody class="thead-light">
                        <tr>
                            <th class="col-md-2 text">{{ trans('general.date') }}</th>
                            <th class="col-md-2 text">{{ trans('general.name') }}</th>
                            <th class="col-md-2 text-right">{{ trans('payroll::general.total', ['type' => trans_choice('payroll::general.benefits', 1)]) }}</th>
                            <th class="col-md-2 text-right">{{ trans('payroll::general.total', ['type' => trans_choice('payroll::general.deductions', 1)]) }}</th>
                            <th class="col-md-2 text-right">{{ trans_choice('payroll::general.salaries', 1) }}</th>
                            <th class="col-md-2 text-right">{{ trans_choice('general.totals', 1) }}</th>
                        </tr>

                        @foreach($payments as $payment)
                            <tr>
                                <td class="col-md-2 text">@date($payment->run_payroll->payment_date)</td>
                                <td class="col-md-2 text">{{ $payment->run_payroll->name}}</td>
                                <td class="col-md-2 text-right">@money($payment->benefit, $payment->run_payroll->currency_code, 1)</td>
                                <td class="col-md-2 text-right">@money($payment->deduction, $payment->run_payroll->currency_code, 1)</td>
                                <td class="col-md-2 text-right">@money( $payment->salary, $payment->run_payroll->currency_code,1)</td>
                                <td class="col-md-2 text-right">@money( $payment->total, $payment->run_payroll->currency_code, 1)</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header with-border">
                    <div class="row align-items-center">
                        <div class="col-6 text-nowrap"><h3 class="mb-0">{{ trans_choice('payroll::general.benefits', 1) }}</h3></div>
                        <div class="col-6 hidden-sm pr-0">
                            <span class="float-right">
                                <button type="button" @click="onBenefit({{ $employee->id }}, '{{trans_choice('payroll::general.benefits', 1)}}')" id="button-benefit" class="btn btn-sm btn-success btn-xs float-right">{{ trans('general.add_new') }}</button>
                           </span>
                        </div>
                    </div>
                </div>
                @include('payroll::partials.employee.benefit.show')
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header with-border">
                    <div class="row align-items-center">
                        <div class="col-6 text-nowrap"><h3 class="mb-0">{{ trans_choice('payroll::general.deductions', 1) }}</h3></div>
                        <div class="col-6 hidden-sm pr-0">
                            <span class="float-right">
                                <button type="button" @click="onDeduction({{ $employee->id }}, '{{trans_choice('payroll::general.deductions', 1) }}')" id="button-deductions" class="btn btn-sm btn-success btn-xs float-right">{{ trans('general.add_new') }}</button>
                           </span>
                        </div>
                    </div>
                </div>
                @include('payroll::partials.employee.deduction.show')
            </div>
        </div>
    </div>

    <component v-bind:is="benefit_html"></component>

    <component v-bind:is="edit_benefit_html"></component>

    <component v-bind:is="show_benefit_html"></component>

    <component v-bind:is="deduction_html"></component>

    <component v-bind:is="edit_deduction_html"></component>

    <component v-bind:is="show_deduction_html"></component>
</div>

@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/employee.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
