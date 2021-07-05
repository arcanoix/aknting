<?php

namespace Modules\Payroll\Http\Controllers\Employees;

use App\Abstracts\Http\Controller;
use Modules\Payroll\Models\Employee\Benefit;
use Modules\Payroll\Models\Employee\Employee;

class EmployeeBenefits extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-payroll-employees')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-payroll-employees')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-payroll-employees')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-payroll-employees')->only('destroy');
    }

    public function destroy(Employee $employee, Benefit $benefit)
    {
        $benefit->delete();

        $response = [
            'success'  => true,
            'error'    => false,
            'redirect' => route('employees.employees.show', ['employee' => $employee->id, 'tab' => 'payroll']),
            'data'     => [],
        ];

        $message = trans('payroll::benefits.delete');

        flash($message)->success();

        return response()->json($response);
    }
}
