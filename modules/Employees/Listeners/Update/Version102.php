<?php

namespace Modules\Employees\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\CreditDebitNotes\Models\CreditsTransaction;

class Version102 extends Listener
{
    const ALIAS = 'employees';

    const VERSION = '1.0.2';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->addEmployeeRole();
    }

    public function addEmployeeRole()
    {
        Artisan::call('company:seed', [
            'company' => company_id(),
            '--class' => 'Modules\Employees\Database\Seeds\Permissions',
        ]);
    }
}
