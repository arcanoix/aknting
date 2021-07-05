<?php

namespace Modules\Payroll\Http\Controllers\PayCalendars;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Import as ImportRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Modules\Payroll\Exports\PayCalendars\PayCalendars as Export;
use Modules\Payroll\Http\Requests\PayCalendar as Request;
use Modules\Payroll\Imports\PayCalendars\PayCalendars as Import;
use Modules\Payroll\Jobs\PayCalendar\CreatePayCalendar;
use Modules\Payroll\Jobs\PayCalendar\DeletePayCalendar;
use Modules\Payroll\Jobs\PayCalendar\UpdatePayCalendar;
use Modules\Payroll\Models\Employee\Employee;
use Modules\Payroll\Models\PayCalendar\PayCalendar;

class PayCalendars extends Controller
{
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-payroll-pay-calendars')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-payroll-pay-calendars')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-payroll-pay-calendars')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-payroll-pay-calendars')->only('destroy');
    }

    public function index()
    {
        $pay_calendars = PayCalendar::collect();

        return view('payroll::pay-calendars.index', compact('pay_calendars'));
    }

    public function show(): RedirectResponse
    {
        return redirect()->route('payroll.pay-calendars.index');
    }

    public function create()
    {
        $types = PayCalendar::getAvailableTypes();

        $employees = Employee::get();
        foreach ($employees as $key => $employee) {
            if (!$employee->contact->enabled) {
                unset($employees[$key]);
            }
        }

        return view('payroll::pay-calendars.create', compact('types', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new CreatePayCalendar($request));

        if ($response['success']) {
            $response['redirect'] = route('payroll.pay-calendars.index');

            $message = trans('messages.success.added', ['type' => trans_choice('payroll::general.pay_calendars', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('payroll.pay-calendars.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function duplicate(PayCalendar $payCalendar): RedirectResponse
    {
        $clone = $payCalendar->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('payroll::general.pay_calendars', 1)]);

        flash($message)->success();

        return redirect()->route('payroll.pay-calendars.edit', $clone->id);
    }

    public function import(ImportRequest $request): JsonResponse
    {
        $response = $this->importExcel(new Import, $request, trans_choice('payroll::general.pay_calendars', 2));

        if ($response['success']) {
            $response['redirect'] = route('payroll.pay-calendars.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['payroll', 'pay-calendars']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    public function edit(PayCalendar $payCalendar)
    {
        $employees = Employee::with('contact')->enabled()->get();

        $types = PayCalendar::getAvailableTypes();

        $pay_day_modes = PayCalendar::getPaydayModes($payCalendar->type);

        $pay_calendar = $payCalendar;

        $pay_calendar->employees = $pay_calendar->employees()->pluck('employee_id')->toArray();

        return view('payroll::pay-calendars.edit', compact('pay_calendar', 'employees', 'types', 'pay_day_modes'));
    }

    public function update(PayCalendar $payCalendar, Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdatePayCalendar($payCalendar, $request));

        if ($response['success']) {
            $response['redirect'] = route('payroll.pay-calendars.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('payroll::general.pay_calendars', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('payroll.pay-calendars.edit', $payCalendar->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function enable(PayCalendar $payCalendar): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdatePayCalendar($payCalendar, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $payCalendar->name]);
        }

        return response()->json($response);
    }

    public function disable(PayCalendar $payCalendar): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdatePayCalendar($payCalendar, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $payCalendar->name]);
        }

        return response()->json($response);
    }

    public function destroy(PayCalendar $payCalendar): JsonResponse
    {
        $response = $this->ajaxDispatch(new DeletePayCalendar($payCalendar));

        $response['redirect'] = route('payroll.pay-calendars.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $payCalendar->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('payroll::general.pay_calendars', 2));
    }

    public function getTypes()
    {
        $types = collect(PayCalendar::getAvailableTypes())
            ->map(function ($type, $key) {
                return [
                    'id'   => $key,
                    'name' => $type,
                ];
            })
            ->values()
            ->all();

        return $this->response('', compact('types'));
    }
}
