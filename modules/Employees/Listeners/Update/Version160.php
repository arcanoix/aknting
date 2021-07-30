<?php

namespace Modules\Employees\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Auth\Role;
use App\Models\Common\Widget;
use App\Traits\Permissions;

class Version160 extends Listener
{
    use Permissions;

    const ALIAS = 'employees';

    const VERSION = '1.6.0';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updatePermissions();

        $this->updateWidgets();
    }

    private function updatePermissions()
    {
        $this->detachPermission('employee', 'read-employees-widgets-profile', true);

        $employee_role = Role::where('name', 'employee')->first();

        if (!$employee_role) {
            return;
        }

        $this->attachPermission($employee_role, 'read-employees-widgets-employee-profile');
    }

    private function updateWidgets()
    {
        Widget::where('class', 'Modules\Employees\Widgets\Profile')
            ->update(['class' => 'Modules\Employees\Widgets\EmployeeProfile']);

        Widget::where('class', 'Modules\Employees\Widgets\EmployeeProfile')
            ->where('name', trans('auth.profile'))
            ->update(['name' => trans_choice('employees::general.employees', 1) . ' ' . trans('auth.profile')]);
    }
}
