@extends('layouts.admin')

@section('title', trans_choice('employees::general.employees', 2))

@section('new_button')
    @permission('create-employees-employees')
        <span>
            <a href="{{ route('employees.employees.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
        </span>
        <span>
            <a href="{{ route('import.create', ['employees', 'employees']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
        </span>
    @endpermission
    <span>
        <a href="{{ route('employees.employees.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
    </span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'employees.employees.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <x-search-string model="Modules\Employees\Models\Employee" />
                </div>

                {{ Form::bulkActionRowGroup('employees::general.employees', $bulk_actions, ['group' => 'employees', 'type' => 'employees']) }}
            {!! Form::close() !!}
        </div>
        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-md-4">@sortablelink('contact.name', trans('general.name'))</th>
                        <th class="col-md-3 hidden-xs">@sortablelink('contact.email',trans('general.email'))</th>
                        <th class="col-md-2 hidden-xs">@sortablelink('hired_at', trans('employees::employees.hired_at'))</th>
                        <th class="col-md-1 hidden-xs">@sortablelink('contact.enabled', trans_choice('general.statuses', 1))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm border-0">{{ Form::bulkActionGroup($employee->id, !empty($employee->contact) ? $employee->contact->name : '-') }}</td>
                            <td class="col-md-4 border-0"><a href="{{ route('employees.employees.show', $employee->id) }}">{{ !empty($employee->contact) ? $employee->contact->name : '-' }}</a></td>
                            <td class="col-md-3 border-0 hidden-xs">{{ !empty($employee->contact) ? $employee->contact->email : '-' }}</td>
                            <td class="col-md-2 border-0 hidden-xs">{{ Date::parse($employee->hired_at)->format('Y-m-d')  }}</td>
                            <td class="col-md-1 border-0 hidden-xs">
                            @if (user()->can('update-employees-employees'))
                                {{ Form::enabledGroup($employee->id, !empty($employee->contact) ? $employee->contact->name : '-', !empty($employee->contact) ? $employee->contact->enabled : false) }}
                            @else
                                @if (!empty($employee->contact) && $employee->contact->enabled)
                                    <badge rounded type="success">{{ trans('general.enabled') }}</badge>
                                @else
                                    <badge rounded type="danger">{{ trans('general.disabled') }}</badge>
                                @endif
                            @endif
                            </td>
                            <td class="col-md-1 border-0 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('employees.employees.edit', $employee->id) }}">{{ trans('general.edit') }}</a>
                                        @permission('create-employees-employees')
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('employees.employees.duplicate', $employee->id) }}">{{ trans('general.duplicate') }}</a>
                                        @endpermission
                                        @permission('delete-employees-employees')
                                            <div class="dropdown-divider"></div>
                                            @php($employee->name = !empty($employee->contact) ? $employee->contact->name : '')
                                            {!! Form::deleteLink($employee, 'employees.employees.destroy', 'employees::general.employees') !!}
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
                @include('partials.admin.pagination', ['items' => $employees])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Employees/Resources/assets/js/employees/index.min.js?v=' . module_version('employees')) }}"></script>
@endpush
