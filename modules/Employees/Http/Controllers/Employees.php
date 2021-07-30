<?php

namespace Modules\Employees\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Jobs\Common\UpdateContact;
use App\Models\Setting\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Modules\Employees\Exports\Employees as Export;
use Modules\Employees\Http\Requests\Employee as Request;
use Modules\Employees\Imports\Employees as Import;
use Modules\Employees\Jobs\Employee\CreateEmployee;
use Modules\Employees\Jobs\Employee\CreateEmployeeContact;
use Modules\Employees\Jobs\Employee\DeleteEmployee;
use Modules\Employees\Jobs\Employee\UpdateEmployee;
use Modules\Employees\Jobs\Employee\UpdateEmployeeContact;
use Modules\Employees\Models\Employee;
use Modules\Employees\Models\Position;

class Employees extends Controller
{
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-employees-employees')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-employees-employees')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-employees-employees')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-employees-employees')->only('destroy');
    }

    public function index()
    {
        $employees = Employee::with('contact')->collect('contact.name');

        return view('employees::employees.index', compact('employees'));
    }

    public function show(Employee $employee)
    {
        return view('employees::employees.show', compact('employee'));
    }

    public function create()
    {
        $positions = Position::enabled()->orderBy('name')->pluck('name', 'id');

        $genders = Employee::getAvailableGenders();

        $currencies = Currency::enabled()->pluck('name', 'code');

        $currency = Currency::where('code', '=', setting('default.currency'))->first();

        $file_types = $this->getFileTypes();

        return view('employees::employees.create', compact('positions', 'genders', 'currencies', 'currency', 'file_types'));
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new CreateEmployeeContact($request));

        if ($response['success']) {
            $contact = $response['data'];

            $request['contact_id'] = $contact->id;

            $this->dispatch(new CreateEmployee($request));

            $response['redirect'] = route('employees.employees.index');

            $message = trans('messages.success.added', ['type' => trans_choice('employees::general.employees', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('employees.employees.create');
        }

        return response()->json($response);
    }

    public function edit(Employee $employee)
    {
        if (empty($employee->contact)) {
            return redirect()->back();
        }

        $positions = Position::enabled()->orderBy('name')->pluck('name', 'id');

        $genders = Employee::getAvailableGenders();

        $currencies = Currency::enabled()->pluck('name', 'code');

        $currency = Currency::where('code', '=', $employee->contact->currency_code)->first();

        $file_types = $this->getFileTypes();

        return view('employees::employees.edit', compact('employee', 'positions', 'genders', 'currencies', 'currency', 'file_types'));
    }

    public function update(Employee $employee, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateEmployee($employee, $request));

        if ($response['success']) {
            $response['redirect'] = route('employees.employees.index');

            $message = trans('messages.success.updated', ['type' => $employee->contact->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('employees.employees.edit', $employee->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function destroy(Employee $employee)
    {
        $response = $this->ajaxDispatch(new DeleteEmployee($employee));

        $response['redirect'] = route('employees.employees.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $employee->contact->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function disable(Employee $employee): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdateEmployeeContact($employee->contact, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $employee->contact->name]);
        }

        return response()->json($response);
    }

    public function enable(Employee $employee): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdateContact($employee->contact, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $employee->contact->name]);
        }

        return response()->json($response);
    }

    public function duplicate(Employee $employee): RedirectResponse
    {
        $clone = $employee->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('employees::general.employees', 1)]);

        flash($message)->success();

        return redirect()->route('employees.employees.edit', $clone->id);
    }

    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('employees::general.employees', 2));
    }

    public function import(ImportRequest $request): JsonResponse
    {
        $response = $this->importExcel(new Import, $request, trans_choice('employees::general.employees', 2));

        if ($response['success']) {
            $response['redirect'] = route('employees.employees.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['employees', 'employees']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    private function getFileTypes()
    {
        $file_types = [];

        $file_type_mimes = explode(',', config('filesystems.mimes'));

        foreach ($file_type_mimes as $mime) {
            $file_types[] = '.' . $mime;
        }

        return implode(',', $file_types);
    }
}
