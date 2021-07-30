<?php

namespace Modules\Employees\Jobs\Employee;

use App\Abstracts\Job;
use App\Jobs\Common\DeleteContact;
use Modules\Employees\Events\DetermineRelationships;
use Modules\Employees\Models\Employee;

class DeleteEmployee extends Job
{
    protected $employee;

    protected $contact;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
        $this->contact = $employee->contact;
    }

    public function handle(): bool
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->employee->delete();

            $this->dispatch(new DeleteContact($this->contact));
        });

        return true;
    }

    public function authorize()
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->contact->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    private function getRelationships(): array
    {
        $rel = new class {
            public $relationships = [];
        };

        event(new DetermineRelationships($rel));

        return $this->countRelationships($this->employee, $rel->relationships);
    }
}
