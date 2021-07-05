<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="table-responsive">
            @if ($payrolls->count())
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-xs-4 col-md-3 text-left">{{ trans('general.name')}}</th>
                            <th class="col-xs-4 col-md-1 text-left">{{ trans('payroll::run-payrolls.from_date') }}</th>
                            <th class="col-xs-4 col-md-1 text-left">{{ trans('payroll::run-payrolls.to_date') }}</th>
                            <th class="col-xs-4 col-md-1 text-left">{{ trans('payroll::run-payrolls.payment_date')}}</th>
                            <th class="col-xs-4 col-md-2 text-right">{{ trans_choice('payroll::general.employees', 2) }}</th>
                            <th class="col-xs-4 col-md-2 text-left">{{ trans_choice('general.statuses', 1)}}</th>
                            <th class="col-xs-4 col-md-2 text-right">{{ trans('general.amount')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $item)
                            <tr class="row border-top-1">
                                <td class="col-xs-4 col-md-3 text-left"><a href="{{ url('payroll/run-payrolls/' . $item->id) }}">{{ $item->name }}</a></td>
                                <td class="col-xs-4 col-md-1 text-left">@date($item->from_date)</td>
                                <td class="col-xs-4 col-md-1 text-left">@date($item->to_date)</td>
                                <td class="col-xs-4 col-md-1 text-left">@date($item->payment_date)</td>
                                <td class="col-xs-4 col-md-2 text-right">{{ $item->employees->count() }}</td>
                                <td class="col-xs-4 col-md-2 text-left"><span class="label {{ $item->status_label }}">{{ trans('payroll::run-payrolls.status.' . $item->status) }}</span></td>
                                <td class="col-xs-4 col-md-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <h5 class="text-center">{{ trans('general.no_records') }}</h5>
            @endif
        </div>
    </div>
</div>
