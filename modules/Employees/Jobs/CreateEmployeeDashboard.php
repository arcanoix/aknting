<?php

namespace Modules\Employees\Jobs;

use App\Abstracts\Job;
use App\Jobs\Common\CreateDashboard;
use Modules\Employees\Events\WidgetsCreating;
use Modules\Employees\Widgets\EmployeeProfile;

class CreateEmployeeDashboard extends Job
{
    private $user_id;

    private $company_id;

    private $name;

    private $custom_widgets;

    public function __construct($user_id, $company_id = null, $name = null, $custom_widgets = null)
    {
        $this->user_id = $user_id;
        $this->company_id = $company_id ?? company_id();
        $this->name = $name ?? trans_choice('general.dashboards', 1);
        $this->custom_widgets = $custom_widgets ?? $this->getWidgets();
    }

    public function handle()
    {
        $this->dispatch(new CreateDashboard([
            'users' => [$this->user_id],
            'company_id'     => $this->company_id,
            'name'           => $this->name,
            'custom_widgets' => $this->custom_widgets,
        ]));
    }

    private function getWidgets(): array
    {
        $dashboard_items = new \stdClass();
        $dashboard_items->widgets = [
            EmployeeProfile::class,
        ];

        event(new WidgetsCreating($dashboard_items));

        return $dashboard_items->widgets;
    }
}
