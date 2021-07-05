@extends('layouts.admin')

@section('title', trans_choice('payroll::general.pay_calendars', 2))

@section('new_button')
    @permission('create-payroll-pay-calendars')
        <span class="new-button">
            <a href="{{ route('payroll.pay-calendars.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
        </span>
        <span>
            <a href="{{ route('import.create', ['payroll', 'pay-calendars']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
        </span>
    @endpermission
    <span>
        <a href="{{ route('payroll.pay-calendars.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
    </span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'payroll.pay-calendars.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <x-search-string model="Modules\Payroll\Models\PayCalendar\PayCalendar" />
                </div>

                {{ Form::bulkActionRowGroup('payroll::general.pay_calendars', $bulk_actions, ['group' => 'payroll', 'type' => 'pay-calendars']) }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-md-4">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-3">@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th class="col-md-3">@sortablelink('pay_day_mode', trans('payroll::pay-calendars.pay_day_mode'))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($pay_calendars as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm border-0">{{ Form::bulkActionGroup($item->id, $item->name) }}</td>
                            <td class="col-md-4 border-0"><a href="{{ route('payroll.pay-calendars.edit', $item->id) }}">{{ $item->name }}</a></td>
                            <td class="col-md-3 border-0">{{ trans('payroll::general.' . $item->type) }}</td>
                            <td class="col-md-3 border-0">{{ trans('payroll::general.' . $item->pay_day_mode) }}</td>
                            <td class="col-md-1 border-0 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('payroll.pay-calendars.run-payrolls.create', $item->id ) }}">{{ trans_choice('payroll::general.run_payrolls', 1) }}</a>
                                        <a class="dropdown-item" href="{{ route('payroll.pay-calendars.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @permission('create-payroll-pay-calendars')
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('payroll.pay-calendars.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                        @endpermission
                                        @permission('delete-payroll-pay-calendars')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'payroll.pay-calendars.destroy', 'payroll::general.pay_calendars') !!}
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
                @include('partials.admin.pagination', ['items' => $pay_calendars])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/pay-calendars.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
