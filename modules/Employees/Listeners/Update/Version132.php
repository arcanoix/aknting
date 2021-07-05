<?php

namespace Modules\Employees\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Artisan;

class Version132 extends Listener
{
    use Permissions;

    const ALIAS = 'employees';

    const VERSION = '1.3.2';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updatePermissions();
    }

    public function updatePermissions()
    {
        Artisan::call('company:seed', [
            'company' => company_id(),
            '--class' => 'Modules\Employees\Database\Seeds\Permissions',
        ]);

        $this->detachPermission('employee', 'update-common-items', false);
        $this->detachPermission('employee', 'delete-common-items', false);
    }
}
