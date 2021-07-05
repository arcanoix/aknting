<html lang="{{ app()->getLocale() }}">
    @include('payroll::partials.print.head')

    <body onload="window.print();">
        @stack('body_start')
            <div class="payslip-block">
                <strong>{{ setting('company.name') }}</strong>
                <p>{!! nl2br(setting('company.address')) !!}</p>

                @if (setting('company.phone'))
                    <p>{{ setting('company.phone') }}</p>
                @endif

                <p>{{ setting('company.email') }}</p>
            </div>
            <hr>

            <div class="payslip-block">
                <strong>{{ trans_choice('payroll::general.employees', 1) }}: </strong>{{ $data['name'] }}
            </div>
            <div class="table table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-left w-50">{{ trans('payroll::run-payrolls.payment_date') }}</th>
                            <th class="text-left">{{ trans('general.tax_number') }}</th>
                            <th class="text-left">{{ trans('employees::employees.bank_account_number') }}</th>
                            <th class="text-center">{{ trans_choice('general.payment_methods', 1) }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="w-50" id="employee-payment-date">{{ $data['payment_date'] }}</td>
                            <td id="employee-tax-number">{{ $data['tax_number'] }}</td>
                            <td id="employee-bank-account">{{ $data['bank_account_number'] }}</td>
                            <td class="text-center" id="employee-payment-methods">{{ $data['payment_method'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="table table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-left w-60">{{ trans_choice('employees::general.positions', 1) }}</th>
                            <th class="text-left">{{ trans('payroll::run-payrolls.from_date') }}</th>
                            <th class="text-left">{{ trans('payroll::run-payrolls.to_date') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="w-60" id="employee-position">{{ $data['position'] }}</td>
                            <td id="employee-from-date">{{ $data['from_date'] }}</td>
                            <td id="employee-to-date">{{ $data['to_date'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="row">
                <div class="col-6">
                    <div class="table table-responsive pr-2">
                        <table class="table table-striped table-hover" id="tbl-benefits">
                            <thead>
                                <tr>
                                    <th colspan="2">{{ trans_choice('payroll::general.benefits', 2) }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if($data['salary'])
                                    <tr>
                                        <td class="text-left">{{ trans_choice('payroll::general.salaries', 1) }}</td>
                                        <td class="text-right w-10">{{ $data['salary'] }}</td>
                                    </tr>
                                @endif

                                @foreach($data['benefits'] as $benefit)
                                    <tr>
                                        <td class="text-left">{{ $benefit['name'] }}</td>
                                        <td class="text-right w-10">{{ $benefit['amount'] }}</td>
                                    </tr>
                                 @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-6">
                    <div class="table table-responsive pl-2">
                        <table class="table table-striped table-hover" id="tbl-deductions">
                            <thead>
                                <tr>
                                    <th colspan="2">{{ trans_choice('payroll::general.deductions', 2) }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($data['deductions'] as $deduction)
                                    <tr>
                                        <td class="text-left">{{ $deduction['name'] }}</td>
                                        <td class="text-right w-10">{{ $deduction['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="table table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-right">{{ trans_choice('general.totals', 2) }}</th>
                            <th class="text-right w-10" id="employee-total">{{ $data['total'] }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @stack('body_end')
    </body>
</html>
