<?php

namespace Modules\Employees\Jobs\Employee;

use App\Abstracts\Job;
use Modules\Employees\Events\EmployeeCreated;
use Modules\Employees\Events\EmployeeCreating;
use Modules\Employees\Models\Employee;

class CreateEmployee extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    public function handle(): Employee
    {
        event(new EmployeeCreating($this->request));

        $employee = Employee::create($this->request->all());

        event(new EmployeeCreated($employee, $this->request));

        return $employee;
    }
}
