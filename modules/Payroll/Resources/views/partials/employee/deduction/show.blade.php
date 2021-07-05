<div class="table-responsive">
    @if ($employee->deductions->count())
        <table class="table table-flush table-hover">
            <thead class="thead-light">
                <tr class="row table-head-line">
                    <th class="col-md-4">{{ trans_choice('general.types', 1) }}</th>
                    <th class="col-md-3 text-center">{{ trans('recurring.recurring') }}</th>
                    <th class="col-md-3 text-right">{{ trans('general.amount') }}</th>
                    <th class="col-md-2 text-center">{{ trans('general.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employee->deductions as $deduction)
                    <tr class="row align-items-center border-top-1">
                        <td class="col-md-4 border-0 hidden-xs">
                            <a href="#" class="deduction-show" data-deduction="{{ $deduction->id }}">
                                {{ $deduction->pay_item->pay_item }}
                            </a>
                        </td>
                        <td class="col-md-3 border-0 text-center"> {{ trans('payroll::deductions.deduction_recurring.' . $deduction->recurring) }}</td>
                        <td class="col-md-3 border-0 text-right">@money($deduction->amount, $deduction->currency_code ,1)</td>
                        <td class="col-md-2 border-0 text-center">
                            <div class="dropdown">
                                <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-muted"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" @click="onShowDeduction({{ $deduction->id }}, '{{ trans_choice('payroll::general.deductions', 1) }}')">{{ trans('general.show') }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" @click="onEditDeduction({{ $deduction->id }}, '{{trans('general.title.edit', ['type' => trans_choice('payroll::general.deductions', 1)])  }}')">{{ trans('general.edit') }}</a>
                                    <div class="dropdown-divider"></div>
                                    @php($deduction->pay_item_name = $deduction->pay_item->pay_item)
                                    {!! Form::deleteLink($deduction, company_id() . '/payroll/employees/' . $deduction->employee_id . '/deduction', 'payroll::general.deductions', 'pay_item_name') !!}
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="card-body">
            <h5 class="text-center">{{ trans('general.no_records') }}</h5>
        </div>
    @endif
</div>
