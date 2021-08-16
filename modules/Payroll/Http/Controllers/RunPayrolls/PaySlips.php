<?php

namespace Modules\Payroll\Http\Controllers\RunPayrolls;

use App\Abstracts\Http\Controller;
use App\Utilities\Modules;
use App\Traits\DateTime;
use Date;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Payroll\Models\PayCalendar\PayCalendar;
use Modules\Payroll\Models\RunPayroll\RunPayroll;
use Modules\Payroll\Models\Setting\PayItem;
use Modules\Payroll\Services\RunPayroll as RunPayrollService;

class PaySlips extends Controller
{
    use DateTime;

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

    public function index(PayCalendar $payCalendar, RunPayroll $runPayroll): JsonResponse
    {
        $employees = (new RunPayrollService($runPayroll))->getEmployeesForSelectBox();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => view('payroll::modals.run-payrolls.pay_slips.index', compact('payCalendar', 'runPayroll', 'employees'))->render(),
        ]);
    }

    public function store(PayCalendar $payCalendar, RunPayroll $runPayroll, Request $request): JsonResponse
    {
        $response = [
            'success' => true,
            'error' => false,
            'redirect' => route('payroll.pay-calendars.run-payrolls.approvals.edit', [$payCalendar->id, $runPayroll->id]),
            'data' => [],
        ];

        return response()->json($response);
    }

    public function edit(RunPayroll $runPayroll): JsonResponse
    {
        $payCalendar = $runPayroll->pay_calendar;

        $employees = (new RunPayrollService($runPayroll))->getEmployeesForSelectBox();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => view('payroll::modals.run-payrolls.pay_slips.index', compact('payCalendar', 'runPayroll', 'employees'))->render(),
        ]);
    }

    public function employee(PayCalendar $payCalendar, RunPayroll $runPayroll, $employee_id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'errors' => false,
            'data' => $this->getPaySlipData($runPayroll, $employee_id),
        ]);
    }

    public function print(PayCalendar $payCalendar, RunPayroll $runPayroll, $employee_id)
    {
        return view('payroll::run-payrolls.pay_slips.print', [
            'data' => $this->getPaySlipData($runPayroll, $employee_id),
        ]);
    }

    public function getPaySlipData(RunPayroll $runPayroll, $employee_id): array
    {
        $run_payroll_employee = $runPayroll->employees()
            ->where('employee_id', $employee_id)
            ->first();

        $pay_types = PayItem::pluck('pay_item','id');

        $benefits = $deductions = [];

        $_benefits = $run_payroll_employee->benefits()->where('run_payroll_id', $runPayroll->id)->get();
        foreach ($_benefits as $benefit) {
            $benefits[] = [
                'name' => $pay_types[$benefit->type],
                'amount' => money($benefit->amount, $runPayroll->currency_code, true)->format()
            ];
        }

        $_deductions = $run_payroll_employee->deductions()->where('run_payroll_id', $runPayroll->id)->get();
        foreach ($_deductions as $deduction) {
            $deductions[] = [
                'name' => $pay_types[$deduction->type],
                'amount' => money($deduction->amount, $runPayroll->currency_code, true)->format()
            ];
        }

        $payment_methods = Modules::getPaymentMethods();

        // Share date format
        $date_format = user() ? $this->getCompanyDateFormat() : 'd F Y';

        return [
            'name' => $run_payroll_employee->employee->contact->name,
            'payment_date' => Date::parse($runPayroll->payment_date)->format($date_format),
            'tax_number' => $run_payroll_employee->employee->contact->tax_number ?? '-',
            'bank_account_number' => $run_payroll_employee->employee->bank_account_number ?? '-',
            'payment_method' => $payment_methods[$runPayroll->payment_method],
            'position' => $run_payroll_employee->employee->position->name,
            'from_date' => Date::parse($runPayroll->from_date)->format($date_format),
            'to_date' => Date::parse($runPayroll->to_date)->format($date_format),
            'salary' => money($run_payroll_employee->salary, $runPayroll->currency_code, true)->format(),
            'benefits' => $benefits,
            'deductions' => $deductions,
            'total' => money($run_payroll_employee->total, $runPayroll->currency_code, true)->format()
        ];
    }
}
