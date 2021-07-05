@extends('layouts.admin')

@section('title', trans_choice('payroll::general.run_payrolls', 2))

@section('new_button')
    @permission('create-payroll-run-payrolls')
        <span>
            <a href="{{ route('import.create', ['payroll', 'run-payrolls']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
        </span>
    @endpermission
    <span>
        <a href="{{ route('payroll.run-payrolls.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
    </span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'payroll.run-payrolls.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <x-search-string model="Modules\Payroll\Models\RunPayroll\RunPayroll" />
                </div>

                {{ Form::bulkActionRowGroup('payroll::general.run_payrolls', $bulk_actions, ['group' => 'payroll', 'type' => 'run-payrolls']) }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-md-2">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-1">@sortablelink('from_date', trans('payroll::run-payrolls.from_date'))</th>
                        <th class="col-md-1">@sortablelink('to_date', trans('payroll::run-payrolls.to_date'))</th>
                        <th class="col-md-1">@sortablelink('payment_date',trans_choice('payroll::run-payrolls.payment_date', 1))</th>
                        <th class="col-md-1 text-right">{{ trans_choice('payroll::general.employees', 2) }}</th>
                        <th class="col-md-1">@sortablelink('status', trans_choice('general.statuses', 1))</th>
                        <th class="col-md-3 text-right">@sortablelink('amount', trans('general.amount'))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($payrolls as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm border-0">{{ Form::bulkActionGroup($item->id, $item->name) }}</td>
                            <td class="col-md-2 border-0"><a href="{{ route('payroll.run-payrolls.edit', $item->id) }}">{{ $item->name }}</a></td>
                            <td class="col-md-1 border-0">@date($item->from_date)</td>
                            <td class="col-md-1 border-0">@date($item->to_date)</td>
                            <td class="col-md-1 border-0">@date($item->payment_date)</td>
                            <td class="col-md-1 border-0 text-right">{{ $item->employees->count() }}</td>
                            <td class="col-md-1 border-0"><span class="label {{ $item->status_label }}">{{ trans('payroll::run-payrolls.status.' . $item->status) }}</span></td>
                            <td class="col-md-3 border-0 text-right">@money($item->amount, $item->currency_code, true)</td>
                            <td class="col-md-1 border-0 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        @if ($item->status != 'not_approved')
                                            @permission('update-payroll-run-payrolls')
                                                <a class="dropdown-item" href="{{ route('payroll.run-payrolls.not.approved', $item->id) }}">{{ trans('payroll::run-payrolls.not_approved') }}</a>
                                            @endpermission
                                        @endif
                                        <a class="dropdown-item" href="{{ route('payroll.run-payrolls.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @permission('create-payroll-run-payrolls')
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('payroll.run-payrolls.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                        @endpermission
                                        @permission('delete-payroll-run-payrolls')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'payroll.run-payrolls.destroy', 'payroll::general.run_payrolls') !!}
                                        @endpermission
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer table-action">
            <div class="row align-items-center">
                @include('partials.admin.pagination', ['items' => $payrolls])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/run-payrolls.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
