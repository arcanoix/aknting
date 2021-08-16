<?php

namespace Modules\Payroll\Http\Controllers\RunPayrolls;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Import as ImportRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Modules\Payroll\Exports\RunPayrolls\RunPayrolls as Export;
use Modules\Payroll\Http\Requests\RunPayroll\Start as Request;
use Modules\Payroll\Imports\RunPayrolls\RunPayrolls as Import;
use Modules\Payroll\Jobs\RunPayroll\DeleteRunPayroll;
use Modules\Payroll\Jobs\RunPayroll\DuplicateRunPayroll;
use Modules\Payroll\Models\PayCalendar\PayCalendar;
use Modules\Payroll\Models\RunPayroll\RunPayroll;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;
use Modules\Payroll\Traits\RunPayrolls as TRunPayroll;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RunPayrolls extends Controller
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

    public function index()
    {
        $payrolls = RunPayroll::collect();

        return view('payroll::run-payrolls.index', compact('payrolls'));
    }

    public function show($id)
    {
        $run_payrolls = RunPayrollEmployee::where('run_payroll_id', $id)->get();

        return view('payroll::run-payrolls.show', compact('run_payrolls'));
    }

    public function create(PayCalendar $payCalendar)
    {
        if (!setting('payroll.account')) {
            flash(trans('payroll::run-payrolls.account_is_not_specified'))->error()->important();

            return redirect()->back();
        }

        $pay_calendar = $payCalendar;

        // Steps list
        $steps = [
            'employees' => [
                'title' => trans_choice('payroll::general.employees', 2),
            ],
            'variables' => [
                'title' => trans('payroll::general.variables'),
            ],
            'pay_slips' => [
                'title' => trans('payroll::general.pay_slips'),
            ],
            'approval'  => [
                'title' => trans('payroll::general.approval'),
            ],
            'attachments' => [
                'title' => trans_choice('payroll::run-payrolls.attachments', 2),
            ],
        ];

        return view('payroll::run-payrolls.create', compact('pay_calendar', 'steps'));
    }

    public function duplicate(RunPayroll $runPayroll): RedirectResponse
    {
        $clone = $this->dispatch(new DuplicateRunPayroll($runPayroll));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('payroll.general.run_payrolls', 1)]);

        flash($message)->success();

        return redirect()->route('payroll.run-payrolls.edit', $clone->id);
    }

    public function import(ImportRequest $request): JsonResponse
    {
        $response = $this->importExcel(new Import, $request, trans_choice('payroll::general.run_payrolls', 2));

        if ($response['success']) {
            $response['redirect'] = route('payroll.run-payrolls.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['payroll', 'run-payrolls']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    public function edit(RunPayroll $runPayroll)
    {
        $run_payroll = $runPayroll;

        // Steps list
        $steps = [
            'employees' => [
                'title' => trans_choice('payroll::general.employees', 2),
            ],
            'variables' => [
                'title' => trans('payroll::general.variables'),
            ],
            'pay_slips' => [
                'title' => trans('payroll::general.pay_slips'),
            ],
            'approval'  => [
                'title' => trans('payroll::general.approval'),
            ],
            'attachments' => [
                'title' => trans_choice('payroll::run-payrolls.attachments', 2),
                'path' => route('payroll.pay-calendars.run-payrolls.attachments.edit', [$runPayroll->pay_calendar_id, $runPayroll->id])
            ],
        ];

        return view('payroll::run-payrolls.edit', compact('run_payroll', 'steps'));
    }

    public function update(RunPayroll $runPayroll, Request $request): JsonResponse
    {
        $runPayroll->update($request->input());

        $response = [
            'success'  => true,
            'error'    => false,
            'redirect' => route('payroll.run-payrolls.variables.edit', [$runPayroll->id]),
            'data'     => [],
        ];

        return response()->json($response);
    }

    public function destroy(RunPayroll $runPayroll): JsonResponse
    {
        $response = $this->ajaxDispatch(new DeleteRunPayroll($runPayroll));

        $response['redirect'] = route('payroll.run-payrolls.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $runPayroll->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('payroll::general.run_payrolls', 2));
    }

    public function getStatuses()
    {
        $statuses = collect([
            'not_approved' => trans('payroll::run-payrolls.status.not_approved'),
            'approved'     => trans('payroll::run-payrolls.status.approved'),
        ])
            ->map(function ($status, $key) {
                return [
                    'id'   => $key,
                    'name' => $status,
                ];
            })
            ->values()
            ->all();

        return $this->response('', compact('statuses'));
    }
}
