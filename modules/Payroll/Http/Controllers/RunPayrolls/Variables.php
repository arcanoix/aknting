<?php

namespace Modules\Payroll\Http\Controllers\RunPayrolls;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Currency;
use Modules\Payroll\Models\Employee\Employee;
use Modules\Payroll\Models\PayCalendar\Employee as PayCalendarEmployee;
use Modules\Payroll\Models\PayCalendar\PayCalendar;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeBenefit;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeDeduction;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;
use Modules\Payroll\Models\RunPayroll\RunPayroll;
use Modules\Payroll\Http\Requests\RunPayroll\EmployeeBenefit as BRequest;
use Modules\Payroll\Http\Requests\RunPayroll\EmployeeDeduction as DRequest;
use Modules\Payroll\Models\Setting\PayItem;
use Illuminate\Http\Request;

class Variables extends Controller
{
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

    public function create(PayCalendar $payCalendar, RunPayroll $runPayroll, Request $request)
    {
        $benefit_row = $request->get('benefit_row');

        $deduction_row = $request->get('deduction_row');

        $currency = Currency::where('code', '=', setting('default.currency'))->first();

        if ($currency) {
            // it should be integer for amount mask
            $currency->precision = (int)$currency->precision;
        }

        $benefit_type = PayItem::where('pay_type', 'benefit')->pluck('pay_item', 'id');
        $deduction_type = PayItem::where('pay_type', 'deduction')->pluck('pay_item', 'id');

        $run_payroll = $runPayroll;

        $employees = [];

        $pay_calendar_employees = PayCalendarEmployee::where('pay_calendar_id', $payCalendar->id)
            ->with('employee.contact')
            ->get();
        foreach ($pay_calendar_employees as $pay_calendar_employee) {
            $employees[$pay_calendar_employee->employee_id] = $pay_calendar_employee->employee->contact->name;
        }

        $html = view('payroll::modals.run-payrolls.variables.create', compact('payCalendar', 'deduction_row', 'benefit_row',  'run_payroll', 'employees', 'currency', 'benefit_type', 'deduction_type'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function store(PayCalendar $payCalendar, RunPayroll $runPayroll, Request $request)
    {
        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('payroll.pay-calendars.run-payrolls.pay-slips.index', [$payCalendar->id, $runPayroll->id]),
            'data' => [],
        ];

        $message = trans('messages.success.enabled', ['type' => trans_choice('payroll::general.run_payrolls', 1)]);

        flash($message)->success();

        return response()->json($response);
    }

    public function edit(RunPayroll $runPayroll)
    {
        $benefit_type = PayItem::where('pay_type', 'benefit')->pluck('pay_item', 'id');
        $deduction_type = PayItem::where('pay_type', 'deduction')->pluck('pay_item', 'id');

        $run_payroll = $runPayroll;

        $employees = [];

        $run_payroll_employees = RunPayrollEmployee::where('run_payroll_id', $run_payroll->id)
            ->with('employee.contact')
            ->get();
        foreach ($run_payroll_employees as $run_payroll_employee) {
            $employees[$run_payroll_employee->employee_id] = $run_payroll_employee->employee->contact->name;
        }

        $html = view('payroll::modals.run-payrolls.variables.edit', compact('run_payroll', 'employees', 'benefit_type', 'deduction_type'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function update(RunPayroll $runPayroll, Request $request)
    {
        $message = trans('messages.success.updated', ['type' => trans_choice('payroll::general.run_payrolls', 1)]);

        flash($message)->success();

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('payroll.run-payrolls.pay-slips.edit', $runPayroll->id),
            'data' => [],
        ];

        return response()->json($response);
    }

    public function storeBenefit(RunPayroll $runPayroll, BRequest $request)
    {
        $type = $request->get('type');
        $amount = $request->get('amount');
        $employee = Employee::with('contact.currency')->findOrFail($request->get('employee_id'));
        $currency = $employee->contact->currency;

        $run_payroll = $runPayroll;

        $benefit = $run_payroll->benefits()->create([
            'company_id' => $request['company_id'],
            'employee_id' => $employee->id,
            'pay_calendar_id' => $run_payroll->pay_calendar_id,
            'type' => $type,
            'amount' => $amount,
            'currency_code' => $currency->code,
            'currency_rate' => $currency->rate,
        ]);

        $run_payroll_employee = $run_payroll->employees()
            ->where('employee_id', $employee->id)
            ->first();

        $run_payroll_employee->benefit = (double) $run_payroll_employee->benefit + (double) $amount;
        $run_payroll_employee->total = (double) $run_payroll_employee->total + (double) $amount;
        $run_payroll_employee->save();

        // Run Payroll Update
        $converted_amount = money($amount, $currency->code)
            ->convert(currency($run_payroll->currency_code), $currency->rate)
            ->getAmount();
        $run_payroll->amount = $run_payroll->amount + $converted_amount;
        $run_payroll->save();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => [
                'id' => $benefit->id,
                'name' => PayItem::find($type)->pay_item,
                'amount_format' => money($amount, $currency->code, true)->format(),
                'benefit_total' => money($run_payroll_employee->benefit, $currency->code, true)->format(),
                'total' => money($run_payroll_employee->total, $currency->code, true)->format(),
            ],
            'message' => null,
            'html' => null,
        ]);
    }

    public function destroyBenefit(RunPayroll $runPayroll, RunPayrollEmployeeBenefit $benefit)
    {
        $currency = $benefit->employee->contact->currency;

        $run_payroll_employee = RunPayrollEmployee::where('run_payroll_id', $runPayroll->id)
            ->where('employee_id', $benefit->employee_id)
            ->first();

        $run_payroll_employee->benefit = (double) $run_payroll_employee->benefit - (double) $benefit->amount;
        $run_payroll_employee->total = (double) $run_payroll_employee->total - (double) $benefit->amount;
        $run_payroll_employee->save();

        // Run Payroll Update
        $converted_amount = money($benefit->amount, $currency->code)
            ->convert(currency($runPayroll->currency_code), $currency->rate)
            ->getAmount();
        $runPayroll->amount = $runPayroll->amount - $converted_amount;
        $runPayroll->save();

        $benefit->delete();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => [
                'benefit_total' => money($run_payroll_employee->benefit, $currency->code, true)->format(),
                'total' => money($run_payroll_employee->total, $currency->code, true)->format(),
            ],
            'message' => null,
            'html' => null,
        ]);
    }

    public function storeDeduction(RunPayroll $runPayroll, DRequest $request)
    {
        $type = $request->get('type');
        $amount = $request->get('amount');
        $employee = Employee::with('contact.currency')->findOrFail($request->get('employee_id'));
        $currency = $employee->contact->currency;

        $run_payroll = $runPayroll;

        $deduction = $run_payroll->deductions()->create([
            'company_id' => $run_payroll->company_id,
            'employee_id' => $employee->id,
            'pay_calendar_id' => $run_payroll->pay_calendar_id,
            'type' => $type,
            'amount' => $amount,
            'currency_code' => $currency->code,
            'currency_rate' => $currency->rate,
        ]);

        $run_payroll_employee = $run_payroll->employees()
            ->where('employee_id', $employee->id)
            ->first();

        $run_payroll_employee->deduction += $amount;
        $run_payroll_employee->total -= $amount;
        $run_payroll_employee->save();

        // Run Payroll Update
        $converted_amount = money($amount, $currency->code)
            ->convert(currency($run_payroll->currency_code), $currency->rate)
            ->getAmount();
        $run_payroll->amount = $run_payroll->amount - $converted_amount;
        $run_payroll->save();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => [
                'id' => $deduction->id,
                'name' => PayItem::find($type)->pay_item,
                'amount_format' => money($amount, $currency->code, true)->format(),
                'deduction_total' => money($run_payroll_employee->deduction, $currency->code, true)->format(),
                'total' => money($run_payroll_employee->total, $currency->code, true)->format(),
            ],
            'message' => null,
            'html' => null,
        ]);
    }

    public function destroyDeduction(RunPayroll $runPayroll, RunPayrollEmployeeDeduction $deduction)
    {
        $currency = $deduction->employee->contact->currency;

        $run_payroll_employee = RunPayrollEmployee::where('run_payroll_id', $runPayroll->id)
            ->where('employee_id', $deduction->employee_id)
            ->first();

        $run_payroll_employee->deduction = (double) $run_payroll_employee->deduction - (double) $deduction->amount;
        $run_payroll_employee->total = (double) $run_payroll_employee->total + (double) $deduction->amount;
        $run_payroll_employee->save();

        // Run Payroll Update
        $converted_amount = money($deduction->amount, $currency->code)
            ->convert(currency($runPayroll->currency_code), $currency->rate)
            ->getAmount();
        $runPayroll->amount = $runPayroll->amount + $converted_amount;
        $runPayroll->save();

        $deduction->delete();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => [
                'deduction_total' => money($run_payroll_employee->deduction, $currency->code, true)->format(),
                'total' => money($run_payroll_employee->total, $currency->code, true)->format(),
            ],
            'message' => null,
            'html' => null,
        ]);
    }
}
