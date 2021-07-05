<?php

namespace Modules\Employees\Jobs\Employee;

use App\Abstracts\Job;
use Modules\Employees\Events\EmployeeUpdated;
use Modules\Employees\Events\EmployeeUpdating;
use Modules\Employees\Models\Employee;

class UpdateEmployee extends Job
{
    protected $employee;

    protected $request;

    public function __construct(Employee $employee, $request)
    {
        $this->employee = $employee;
        $this->request = $this->getRequestInstance($request);
    }

    public function handle(): Employee
    {
        event(new EmployeeUpdating($this->employee, $this->request));

        $this->employee->update($this->request->all());

        event(new EmployeeUpdated($this->employee, $this->request));

        return $this->employee;
    }
}
