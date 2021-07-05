<?php

namespace Modules\Payroll\Http\Controllers\RunPayrolls;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Payroll\Models\PayCalendar\PayCalendar;
use Modules\Payroll\Models\RunPayroll\RunPayroll;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;

class Approvals extends Controller
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

    public function edit(PayCalendar $payCalendar, RunPayroll $runPayroll): JsonResponse
    {
        $employees = RunPayrollEmployee::where('run_payroll_id', $runPayroll->id)->get();

        $html = view('payroll::modals.run-payrolls.approvals.index', compact('payCalendar', 'runPayroll', 'employees'))->render();

        return response()->json([
            'success' => true,
            'error'   => false,
            'message' => 'null',
            'html'    => $html,
        ]);
    }

    public function update(PayCalendar $payCalendar, RunPayroll $runPayroll, Request $request): JsonResponse
    {
        $amount = 0;

        foreach ($runPayroll->employees as $employee) {
            $amount += $employee->total;
        }

        $payment = Transaction::updateOrCreate(
            [
                'id' => $runPayroll->payment_id,
            ],
            [
                'company_id'     => $runPayroll->company_id,
                'account_id'     => $runPayroll->account_id,
                'contact_id'     => null,
                'type'           => 'expense',
                'paid_at'        => $request->get('payment_date'),
                'amount'         => $amount,
                'currency_code'  => $runPayroll->currency_code,
                'currency_rate'  => $runPayroll->currency_rate,
                'description'    => null,
                'category_id'    => $runPayroll->category_id,
                'payment_method' => $runPayroll->payment_method,
                'reference'      => trans('payroll::run-payrolls.reference', ['id' => $runPayroll->name]),
            ]
        );

        $runPayroll->payment_date = $request->get('payment_date');
        $runPayroll->status = 'approved';
        $runPayroll->amount = $amount;
        $runPayroll->payment_id = $payment->id;
        $runPayroll->save();

        $response = [
            'success'  => true,
            'error'    => false,
            'redirect' => route('payroll.pay-calendars.run-payrolls.attachments.edit', [$payCalendar->id, $runPayroll->id]),
            'data'     => [],
        ];

        $message = trans('messages.success.enabled', ['type' => trans_choice('payroll::general.run_payrolls', 1)]);

        flash($message)->success();

        return response()->json($response);
    }

    public function not_approved(RunPayroll $runPayroll)
    {
        $runPayroll->status = 'not_approved';

        Transaction::where('id', $runPayroll->payment_id)->delete();

        $runPayroll->save();

        $message = trans('messages.success.disabled', ['type' => trans_choice('payroll::general.run_payrolls', 1)]);

        flash($message)->success();

        return redirect()->route('payroll.run-payrolls.index');
    }
}
