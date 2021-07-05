<?php

namespace Modules\Payroll\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use Illuminate\Support\Facades\DB;

class Version213 extends Listener
{
    const ALIAS = 'payroll';

    const VERSION = '2.1.3';

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

        $this->updateReportsSettings();
    }

    protected function updateReportsSettings()
    {
        $classes = [
            'Modules\Payroll\Reports\EmployeeSummary',
            'Modules\Payroll\Reports\EmployeeDetailed',
        ];

        $reports = DB::table('reports')->whereIn('class', $classes)->cursor();
        foreach ($reports as $report) {
            DB::table('reports')
                ->whereId($report->id)
                ->update([
                    'settings' => array_merge(json_decode($report->settings, true), ['group' => 'employee']),
                ]);
        }
    }
}
