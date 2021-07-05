<?php

namespace Modules\Payroll\Widgets;

use App\Abstracts\Widget;

use Modules\Payroll\Models\PayCalendar\PayCalendar;

class TotalPayCalendars extends Widget
{
    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function getDefaultName()
    {
        return trans('payroll::general.total', ['type' => trans_choice('payroll::general.pay_calendars', 2)]);
    }

    public function show()
    {
        $total_pay_calendars = PayCalendar::get()->count();

        return $this->view('payroll::widgets.total_pay_calendars', [
            'total_pay_calendars' => $total_pay_calendars,
        ]);
    }
}
