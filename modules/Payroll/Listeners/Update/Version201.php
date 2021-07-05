<?php

namespace Modules\Payroll\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use Illuminate\Support\Facades\Artisan;

class Version201 extends Listener
{
    const ALIAS = 'payroll';

    const VERSION = '2.0.1';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        Artisan::call('company:seed', [
            'company' => company_id(),
            '--class' => 'Modules\Payroll\Database\Seeds\PayItems',
        ]);

        Artisan::call('migrate', ['--force' => true]);
    }
}
