<?php

namespace Modules\Employees\Events;

use App\Abstracts\Event;
use Modules\Employees\Models\Employee;

class EmployeeUpdated extends Event
{
    public $employee;

    public $request;

    public function __construct(Employee $employee, $request)
    {
        $this->employee = $employee;
        $this->request = $request;
    }
}
