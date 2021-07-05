<?php

namespace Modules\Payroll\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Models\Common\Widget;
use App\Traits\Jobs;
use App\Traits\Permissions;

class Version231 extends Listener
{
    use Jobs, Permissions;

    const ALIAS = 'payroll';

    const VERSION = '2.3.1';

    public function handle(UpdateFinished $event): void
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->migrateWidgets();
    }

    protected function migrateWidgets()
    {
        company()->users()->each(function ($user) {
            if ($user->cannot('read-admin-panel')) {
                return;
            }

            $dashboard_hr = $user->dashboards()->where([
                'company_id' => company_id(),
                'name'       => trans('employees::general.hr'),
                'enabled'    => 1,
            ])->first();

            if (!$dashboard_hr) {
                return;
            }

            $sort = $dashboard_hr->widgets()->max('sort');
            $sort = $sort ? $sort + 1 : 1;

            $dashboard_payroll = $user->dashboards()->where([
                'company_id' => company_id(),
                'name'       => trans('payroll::general.name'),
                'enabled'    => 1,
            ])->first();

            $widgets = [
                'Modules\Payroll\Widgets\TotalPayrolls',
                'Modules\Payroll\Widgets\TotalPayCalendars',
                'Modules\Payroll\Widgets\LatestPayRunRecords',
                'Modules\Payroll\Widgets\PayrollHistory',
            ];

            foreach ($widgets as $class_name) {
                $widget = $dashboard_payroll ? $dashboard_payroll->widgets()->where('class', $class_name)->first() : null;

                Widget::create([
                    'company_id'   => company_id(),
                    'dashboard_id' => $dashboard_hr->id,
                    'class'        => $class_name,
                    'name'         => $widget ? $widget->name : (new $class_name())->getDefaultName(),
                    'settings'     => $widget ? $widget->settings : (new $class_name())->getDefaultSettings(),
                    'sort'         => $sort,
                ]);

                $sort++;
            }

            if ($dashboard_payroll) {
                $user->dashboards()->detach($dashboard_payroll);
                $dashboard_payroll->widgets()->delete();
                $dashboard_payroll->delete();
            }
        });
    }
}
