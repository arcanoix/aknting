<?php

namespace Modules\Employees\Jobs\Employee;

use App\Abstracts\Job;
use Modules\Employees\Events\EmployeeCreated;
use Modules\Employees\Events\EmployeeCreating;
use Modules\Employees\Models\Employee;

class CreateEmployee extends Job
{
    protected $employee;

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

        \DB::transaction(function () {
            $this->employee = Employee::create($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'employees');

                    $this->employee->attachMedia($media, 'attachment');
                }
            }
        });

        event(new EmployeeCreated($this->employee, $this->request));

        return $this->employee;
    }
}
