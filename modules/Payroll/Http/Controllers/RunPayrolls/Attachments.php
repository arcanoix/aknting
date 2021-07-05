<?php

namespace Modules\Payroll\Http\Controllers\RunPayrolls;

use App\Abstracts\Http\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Payroll\Http\Requests\RunPayroll\Attachments as Request;
use Modules\Payroll\Jobs\RunPayroll\UpdateRunPayrollAttachments;
use Modules\Payroll\Models\PayCalendar\PayCalendar;
use Modules\Payroll\Models\RunPayroll\RunPayroll;
use Modules\Payroll\Traits\RunPayrolls as TRunPayroll;

class Attachments extends Controller
{
    use TRunPayroll;

    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:read-payroll-run-payrolls')->only(['edit']);
        $this->middleware('permission:update-payroll-run-payrolls')->only(['update']);
    }

    public function edit(PayCalendar $payCalendar, RunPayroll $runPayroll): JsonResponse
    {
        $file_type_mimes = explode(',', config('filesystems.mimes'));

        $file_types = [];

        foreach ($file_type_mimes as $mime) {
            $file_types[] = '.' . $mime;
        }

        $file_types = implode(',', $file_types);

        $html = view('payroll::modals.run-payrolls.attachments.edit', compact('payCalendar', 'runPayroll', 'file_types'))->render();

        return response()->json([
            'success' => true,
            'error'   => false,
            'message' => 'null',
            'html'    => $html,
        ]);
    }

    public function update(PayCalendar $payCalendar, RunPayroll $runPayroll, Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdateRunPayrollAttachments($runPayroll, $request));

        if ($response['success']) {
            $response['redirect'] = route('payroll.run-payrolls.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('payroll::general.run_payrolls', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }
}
