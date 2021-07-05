<?php

namespace Modules\Payroll\Widgets;

use App\Abstracts\Widget;
use Modules\Payroll\Models\RunPayroll\RunPayroll;

class TotalPayrolls extends Widget
{
    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function getDefaultName()
    {
        return trans('payroll::general.total', ['type' => trans('payroll::general.payrolls')]);
    }

    public function show()
    {
        $total_expenses = RunPayroll::where('status', 'approved')->sum('amount');

        return $this->view('payroll::widgets.total_payrolls', [
            'total_expenses' => $total_expenses,
        ]);
    }
}
