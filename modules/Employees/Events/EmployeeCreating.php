<?php

namespace Modules\Employees\Events;

use App\Abstracts\Event;

class EmployeeCreating extends Event
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }
}
