<?php

namespace Modules\Payroll\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Models\Common\Widget;
use App\Traits\Jobs;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Artisan;
use Modules\Employees\Jobs\CreateEmployeeDashboard;
use Modules\Payroll\Widgets\PaychecksReceived;
use Modules\Payroll\Widgets\TotalBenefits;
use Modules\Payroll\Widgets\TotalSalary;

class Version260 extends Listener
{
    use Jobs, Permissions;

    const ALIAS = 'payroll';

    const VERSION = '2.6.0';

    public function handle(UpdateFinished $event): void
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->createWidgets();
        $this->updatePermissions();
    }

    protected function createWidgets()
    {
        company()->users()->each(function ($user) {
            if ($user->cannot('read-admin-panel')) {
                return;
            }

            if (!$user->hasRole('employee')) {
                return;
            }

            $dashboard = $user->dashboards()->where([
                'company_id' => company_id(),
                'name'       => trans_choice('general.dashboards', 1),
                'enabled'    => 1,
            ])->first();

            if (!$dashboard) {
                $this->dispatch(new CreateEmployeeDashboard($user->id));

                return;
            }

            if (!$dashboard) {
                return;
            }

            $sort = $dashboard->widgets()->count() + 1;

            $widgets = [
                PaychecksReceived::class,
                TotalSalary::class,
                TotalBenefits::class,
            ];

            foreach ($widgets as $widget_class) {
                $this->createWidget($dashboard, $widget_class, $sort);

                $sort++;
            }
        });
    }

    protected function createWidget($dashboard, string $widget_class, int $sort): void
    {
        Widget::firstOrCreate([
            'company_id'   => $dashboard->company_id,
            'dashboard_id' => $dashboard->id,
            'class'        => $widget_class,
        ], [
            'name'     => (new $widget_class())->getDefaultName(),
            'sort'     => $sort,
            'settings' => (new $widget_class())->getDefaultSettings(),
        ]);
    }

    private function updatePermissions()
    {
        Artisan::call('company:seed', [
            'company' => company_id(),
            '--class' => 'Modules\Payroll\Database\Seeds\Permissions',
        ]);
    }
}
