<?php

namespace Modules\Payroll\Widgets;

use App\Abstracts\Widget;
use App\Traits\DateTime;
use App\Utilities\Chartjs;
use Date;
use Modules\Payroll\Models\RunPayroll\RunPayroll;

class PayrollHistory extends Widget
{
    use DateTime;

    public $default_settings = [
        'width' => 'col-md-12',
    ];

    public function getDefaultName()
    {
        return trans('payroll::dashboard.chart');
    }

    public function show()
    {
        $financial_start = $this->getFinancialStart()->format('Y-m-d');

        // check and assign year start
        if (($year_start = Date::today()->startOfYear()->format('Y-m-d')) !== $financial_start) {
            $year_start = $financial_start;
        }

        $start = Date::parse(request('start', $year_start));
        $end = Date::parse(request('end', Date::parse($year_start)->addYear(1)->subDays(1)->format('Y-m-d')));
        $period = request('period', 'month');
        $range = request('range', 'custom');

        $start_month = $start->month;
        $end_month = $end->month;

        // Monthly
        $labels = [];

        $s = clone $start;

        if ($range == 'last_12_months') {
            $end_month   = 12;
            $start_month = 0;
        } elseif ($range == 'custom') {
            $end_month   = $end->diffInMonths($start);
            $start_month = 0;
        }

        for ($j = $end_month; $j >= $start_month; $j--) {
            $labels[$end_month - $j] = $s->format('M Y');

            if ($period == 'month') {
                $s->addMonth();
            } else {
                $s->addMonths(3);
                $j -= 2;
            }
        }

        $expense = $this->calculatePayRunTotals($start, $end, $period);

        $chart = new Chartjs();
        $chart->type('line')
            ->width(0)
            ->height(300)
            ->options($this->getLineChartOptions())
            ->labels(array_values($labels));

        $chart->dataset(trans_choice('general.expenses', 2), 'line', array_values($expense))
            ->backgroundColor('#ef3232')
            ->color('#ef3232')
            ->options([
                'borderWidth' => 4,
                'pointStyle' => 'line',
            ])
            ->fill(false);
        return $this->view('widgets.line_chart', [
            'chart' => $chart,
        ]);
    }

    private function calculatePayRunTotals($start, $end, $period)
    {
        $totals = [];

        $date_format = 'Y-m';

        if ($period == 'month') {
            $n = 1;
            $start_date = $start->format($date_format);
            $end_date = $end->format($date_format);
            $next_date = $start_date;
        } else {
            $n = 3;
            $start_date = $start->quarter;
            $end_date = $end->quarter;
            $next_date = $start_date;
        }

        $s = clone $start;

        while ($next_date <= $end_date) {
            $totals[$next_date] = 0;

            if ($period == 'month') {
                $next_date = $s->addMonths($n)->format($date_format);
            } else {
                if (isset($totals[4])) {
                    break;
                }

                $next_date = $s->addMonths($n)->quarter;
            }
        }

        $items = RunPayroll::where('status', 'approved')
            ->whereBetween('payment_date', [$start, $end])
            ->get();

        $this->setRunPayrollTotals($totals, $items, $date_format, $period);

        return $totals;
    }

    private function setRunPayrollTotals(&$totals, $items, $date_format, $period)
    {
        foreach ($items as $item) {
            if ($period == 'month') {
                $i = Date::parse($item->payment_date)->format($date_format);
            } else {
                $i = Date::parse($item->payment_date)->quarter;
            }

            if (!isset($totals[$i])) {
                continue;
            }

            $totals[$i] += $item->getAmountConvertedToDefault();
       }
    }
}
