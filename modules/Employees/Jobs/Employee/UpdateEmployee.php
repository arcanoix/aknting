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

        \DB::transaction(function () {
            // Upload attachment
            if ($this->request->file('attachment')) {
                $this->deleteMediaModel($this->employee, 'attachment', $this->request);

                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'employees');

                    $this->employee->attachMedia($media, 'attachment');
                }
            } elseif (!$this->request->file('attachment') && $this->employee->attachment) {
                $this->deleteMediaModel($this->employee, 'attachment', $this->request);
            }

            $this->employee->update($this->request->all());

            $this->dispatch(new UpdateEmployeeContact($this->employee->contact, $this->request));
        });

        event(new EmployeeUpdated($this->employee, $this->request));

        return $this->employee;
    }
}
