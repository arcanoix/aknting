<?php

namespace Modules\Payroll\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\Company;
use App\Models\Common\Dashboard;
use App\Models\Common\Widget;
use Illuminate\Database\Seeder;

class Widgets extends Seeder
{
    private $company_id;

    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $this->company_id = $this->command->argument('company');

        Company::find($this->company_id)->users()->each(function ($user) {
            if ($user->cannot('read-admin-panel')) {
                return;
            }

            $dashboard = $user->dashboards()->where([
                'company_id' => $this->company_id,
                'name' => trans('employees::general.hr'),
                'enabled' => 1,
            ])->first();

            if ($dashboard) {
                $this->createWidgets($dashboard);
            }
        });
    }

    private function createWidgets(Dashboard $dashboard)
    {
        $widgets = [
            'Modules\Payroll\Widgets\TotalPayrolls',
            'Modules\Payroll\Widgets\TotalPayCalendars',
            'Modules\Payroll\Widgets\LatestPayRunRecords',
            'Modules\Payroll\Widgets\PayrollHistory',
        ];

        $sort = $dashboard->widgets()->count() + 1;

        foreach ($widgets as $class_name) {
            $class = new $class_name();

            Widget::create([
                'company_id'   => $this->company_id,
                'dashboard_id' => $dashboard->id,
                'class'        => $class_name,
                'name'         => $class->getDefaultName(),
                'settings'     => $class->getDefaultSettings(),
                'sort'         => $sort,
            ]);

            $sort++;
        }
    }
}
