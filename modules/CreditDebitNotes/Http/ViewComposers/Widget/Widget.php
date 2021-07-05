<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\Widget;

abstract class Widget
{
    protected function applyFilters($model, $args = ['date_field' => 'paid_at'])
    {
        if (empty(request()->get('start_date', null))) {
            return $model;
        }

        $start_date = request()->get('start_date') . ' 00:00:00';
        $end_date = request()->get('end_date') . ' 23:59:59';

        return $model->whereBetween($args['date_field'], [$start_date, $end_date]);
    }
}
