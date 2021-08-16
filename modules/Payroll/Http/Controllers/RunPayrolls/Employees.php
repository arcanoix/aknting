<?php

namespace Modules\Payroll\Http\Controllers\RunPayrolls;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Utilities\Modules;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modules\Payroll\Http\Requests\RunPayroll\Start as Request;
use Modules\Payroll\Jobs\RunPayroll\CreateRunPayroll;
use Modules\Payroll\Models\Employee\Employee;
use Modules\Payroll\Models\PayCalendar\Employee as PayCalendarEmployee;
use Modules\Payroll\Models\PayCalendar\PayCalendar;
use Modules\Payroll\Models\RunPayroll\RunPayroll;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;
use Modules\Payroll\Services\RunPayroll as RunPayrollService;
use Modules\Payroll\Traits\RunPayrolls as TRunPayroll;

class Employees extends Controller
{
    use TRunPayroll;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-payroll-run-payrolls')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-payroll-run-payrolls')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-payroll-run-payrolls')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-payroll-run-payrolls')->only('destroy');
    }

    public function create(PayCalendar $payCalendar): JsonResponse
    {
        $pay_calendar = $payCalendar;

        $pay_calendar_employees = PayCalendarEmployee::where('pay_calendar_id', $payCalendar->id)
            ->with(['employee.position', 'employee.contact'])
            ->get();

        $run_payroll_service = new RunPayrollService(new RunPayroll([
            'from_date' => Carbon::now()->startOfMonth(),
            'to_date'   => Carbon::now()->endOfMonth(),
        ]));

        $employees_data = [];

        foreach ($pay_calendar_employees as $pay_calendar_employee) {
            $employee = $pay_calendar_employee->employee;

            $benefits = $run_payroll_service->determineBenefits($employee)->sum('amount');

            $deductions = $run_payroll_service->determineDeductions($employee)->sum('amount');

            $employees_data[] = [
                'employee'   => $employee,
                'benefits'   => $benefits,
                'deductions' => $deductions,
                'totals'     => $employee->amount + $benefits - $deductions,
            ];
        }

        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $account_currency_code = Account::where('id', setting('payroll.account'))->pluck('currency_code')->first();

        $currency = Currency::where('code', $account_currency_code)->first();

        $categories = Category::enabled()->type('expense')->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $number = $this->getNextRunPayrollNumber();

        $html = view('payroll::modals.run-payrolls.employees.create', compact('pay_calendar', 'employees_data', 'accounts', 'categories', 'currency', 'payment_methods', 'number'))->render();

        return response()->json([
            'success' => true,
            'error'   => false,
            'message' => 'null',
            'html'    => $html,
        ]);
    }

    public function store(PayCalendar $payCalendar, Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new CreateRunPayroll($payCalendar, $request));

        if ($response['success']) {
            $response['redirect'] = route('payroll.pay-calendars.run-payrolls.variables.create', [$payCalendar->id, $response['data']->id]);

            $message = trans('messages.success.enabled', ['type' => trans_choice('payroll::general.employees', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('payroll.pay-calendars.run-payrolls.create', [$payCalendar->id]);

            $message = trans('payroll::general.run_payroll_error');

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function edit(RunPayroll $runPayroll): JsonResponse
    {
        $run_payroll = $runPayroll;

        $employees = RunPayrollEmployee::where('run_payroll_id', $run_payroll->id)->get();

        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $account_currency_code = Account::where('id', setting('payroll.account'))->pluck('currency_code')->first();

        $currency = Currency::where('code', $account_currency_code)->first();

        $categories = Category::enabled()->type('expense')->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $html = view('payroll::modals.run-payrolls.employees.edit', compact('run_payroll', 'employees', 'accounts', 'categories', 'currency', 'payment_methods'))->render();

        return response()->json([
            'success' => true,
            'error'   => false,
            'message' => 'null',
            'html'    => $html,
        ]);
    }

    public function update(RunPayroll $runPayroll, Request $request): JsonResponse
    {
        $runPayroll->update($request->input());

        $message = trans('messages.success.updated', ['type' => trans_choice('payroll::general.pay_calendars', 1)]);

        flash($message)->success();

        $response = [
            'success'  => true,
            'error'    => false,
            'redirect' => route('payroll.run-payrolls.variables.edit', $runPayroll->id),
            'data'     => [],
        ];

        return response()->json($response);
    }

    public function employee(RunPayroll $runPayroll, Employee $employee): JsonResponse
    {
        $benefits = $runPayroll->benefits()->where('employee_id', $employee->id)->get();

        $deductions = $runPayroll->deductions()->where('employee_id', $employee->id)->get();

        $total_amount = $employee->amount;

        $currency_code = $employee->contact->currency_code;

        // Get currency object
        $currency = Currency::where('code', $currency_code)->first();

        $total_benefit = $total_deduction = 0;

        // Benefits
        foreach ($benefits as $benefit) {
            $benefit->name = $benefit->pay_item->pay_item;
            $benefit->amount_format = money($benefit->amount, $currency_code, true)->format();

            $total_amount += $benefit->amount;
            $total_benefit += $benefit->amount;
        }

        // Deductions
        foreach ($deductions as $deduction) {
            $deduction->name = $deduction->pay_item->pay_item;
            $deduction->amount_format = money($deduction->amount, $currency_code, true)->format();

            $total_amount -= $deduction->amount;
            $total_deduction += $deduction->amount;
        }

        $json = [
            'success' => true,
            'errors'  => false,
            'data'    => [
                'name'            => $employee->name,
                'currency'        => $currency,
                'salary'          => money($employee->amount, $currency_code, true)->format(),
                'benefits'        => $benefits,
                'total_benefit'   => money($total_benefit, $currency_code, true)->format(),
                'deductions'      => $deductions,
                'total_deduction' => money($total_deduction, $currency_code, true)->format(),
                'total_amount'    => money($total_amount, $currency_code, true)->format()
            ],
        ];

        return response()->json($json);
    }
}
