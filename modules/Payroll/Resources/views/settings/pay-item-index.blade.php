<div class="table-responsive">
    <table class="table table-flush table-hover">
        <thead class="thead-light">
            <tr class="row table-head-line">
                <th class="col-md-4">{{ trans('payroll::settings.pay_type') }}</th>
                <th class="col-md-3 hidden-xs">{{ trans('payroll::settings.pay_item') }}</th>
                <th class="col-md-3 hidden-xs">{{ trans('payroll::settings.amount_type') }}</th>
                <th class="col-md-2 text-center">{{ trans('general.actions') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach($pay_items as $item)
                <tr class="row align-items-center border-top-1">
                    <td class="col-md-4 hidden-xs  border-0">{{ trans('payroll::settings.type.'.$item->pay_type) }}</td>
                    <td class="col-md-3 hidden-xs  border-0">{{ $item->pay_item }}</td>
                    <td class="col-md-3 hidden-xs  border-0">{{ trans('payroll::settings.type.'.$item->amount_type) }}</td>
                    <td class="col-md-2 text-center border-0">
                        <div class="dropdown">
                            <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-h text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" @click="onEditPayitem({{ $item->id }}, '{{trans('general.title.edit', ['type' => trans('payroll::settings.pay_item')])  }}')">{{ trans('general.edit') }}</a>
                                <div class="dropdown-divider"></div>
                                {!! Form::deleteLink($item, 'payroll.settings.pay-item.destroy', 'payroll::settings.pay_item', 'pay_item') !!}
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

