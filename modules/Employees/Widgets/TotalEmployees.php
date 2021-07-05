<?php

namespace Modules\Employees\Widgets;

use App\Abstracts\Widget;
use Modules\Employees\Models\Employee;

class TotalEmployees extends Widget
{
    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function getDefaultName()
    {
        return trans('employees::general.total', ['type' => trans_choice('employees::general.employees', 2)]);
    }

    public function show()
    {
        return $this->view('employees::widgets.total_employees', [
            'total_employees' => Employee::enabled()->count(),
        ]);
    }
}
