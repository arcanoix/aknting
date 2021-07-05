<?php

namespace Modules\Payroll\Listeners;

use App\Events\Module\Installed as Event;
use App\Traits\Contacts;
use Artisan;

class FinishInstallation
{
    use Contacts;

    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != 'payroll') {
            return;
        }

        $this->addContactTypeByEmployee();
        $this->callSeeds();
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => company_id(),
            '--class' => 'Modules\Payroll\Database\Seeds\Install',
        ]);
    }

    protected function addContactTypeByEmployee()
    {
        setting()->setExtraColumns(['company_id' => company_id()]);
        setting()->forgetAll();
        setting()->load(true);

        $this->addVendorType('employee');
    }
}
