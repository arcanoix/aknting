<?php

namespace Modules\Employees\BulkActions;

use App\Abstracts\BulkAction;
use App\Jobs\Common\UpdateContact;
use Modules\Employees\Jobs\Employee\DeleteEmployee;
use Modules\Employees\Jobs\Employee\UpdateEmployeeContact;
use Modules\Employees\Models\Employee;

class Employees extends BulkAction
{
    public $model = Employee::class;

    public $actions = [
        'enable'  => [
            'name'       => 'general.enable',
            'message'    => 'bulk_actions.message.enable',
            'permission' => 'update-employees-employees',
        ],
        'disable' => [
            'name'       => 'general.disable',
            'message'    => 'bulk_actions.message.disable',
            'permission' => 'update-employees-employees',
        ],
        'delete'  => [
            'name'       => 'general.delete',
            'message'    => 'bulk_actions.message.delete',
            'permission' => 'delete-employees-employees',
        ],
    ];

    public function destroy($request)
    {
        $items = $this->getSelectedRecords($request);

        foreach ($items as $item) {
            try {
                $this->dispatch(new DeleteEmployee($item));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function disable($request)
    {
        $employees = $this->getSelectedRecords($request);

        foreach ($employees as $employee) {
            try {
                if ($contact = $employee->contact) {
                    $this->dispatch(new UpdateEmployeeContact($contact, ['enabled' => 0]));
                }
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function enable($request)
    {
        $employees = $this->getSelectedRecords($request);

        foreach ($employees as $employee) {
            try {
                if ($contact = $employee->contact) {
                    $this->dispatch(new UpdateContact($contact, ['enabled' => 1]));
                }
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
