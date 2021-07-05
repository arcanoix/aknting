<?php

namespace Modules\Employees\Listeners;

use App\Events\Module\Installed as Event;
use App\Traits\Contacts;
use App\Traits\Permissions;
use Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class FinishInstallation
{
    use Contacts, Permissions;

    public $alias = 'employees';

    public function handle(Event $event)
    {
        if ($event->alias != $this->alias) {
            return;
        }

        $this->updatePermissions();
        $this->addCustomContactType();
        $this->callSeeds();
    }

    protected function updatePermissions()
    {
        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsToAdminRoles([
            $this->alias . '-employees' => 'c,r,u,d',
            $this->alias . '-positions' => 'c,r,u,d',
        ]);

        $this->attachModuleWidgetPermissions($this->alias);
    }

    protected function addCustomContactType()
    {
        setting()->setExtraColumns(['company_id' => company_id()]);
        setting()->forgetAll();
        setting()->load(true);

        $this->addVendorType('employee');
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => company_id(),
            '--class' => 'Modules\Employees\Database\Seeds\EmployeesDatabaseSeeder',
        ]);
    }
}
