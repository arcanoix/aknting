<?php

namespace Modules\Payroll\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Traits\Jobs;
use App\Traits\Permissions;
use Illuminate\Support\Facades\File;

class Version240 extends Listener
{
    use Jobs, Permissions;

    const ALIAS = 'payroll';

    const VERSION = '2.4.0';

    public function handle(UpdateFinished $event): void
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->copyFiles();
    }

    protected function copyFiles()
    {
        $files = [
            'modules/Payroll/Resources/assets/pay_calendars.xlsx' => 'public/files/import/pay_calendars.xlsx',
            'modules/Payroll/Resources/assets/run_payrolls.xlsx'  => 'public/files/import/run_payrolls.xlsx',
        ];

        foreach ($files as $source => $target) {
            File::copy(base_path($source), base_path($target));
        }
    }
}
