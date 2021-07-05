<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('general.name') }}</b><a class="float-right text-xs">{{ $benefit->pay_item->pay_item}}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('general.amount') }}</b> <a class="float-right text-xs">@money($benefit->amount, setting('default.currency'), true)</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('recurring.recurring') }}</b> <a class="float-right text-xs">{{ trans('payroll::benefits.benefit_recurring.' . $benefit->recurring) }}</a>
</div>
<div class="card-header border-bottom-1 show-transaction-card-header">
    <b class="text-sm font-weight-600">{{ trans('general.description') }}</b> <a class="float-right text-xs">{{ $benefit->description }}</a>
</div>
