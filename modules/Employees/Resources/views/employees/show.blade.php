@extends('layouts.admin')

@section('title', $employee->contact->name)

@section('content')
    @stack('employee_content_start')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-wrapper">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    @stack('employee_profile_tab_start')
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0 active" id="profile-tab" data-toggle="tab" href="#profile-content" role="tab" aria-controls="profile-content" aria-selected="true">{{ trans('auth.profile') }}</a>
                    </li>
                    @stack('employee_profile_tab_end')
                </ul>
            </div>

            <div class="tab-content" id="myTabContent">
                @stack('employee_profile_content_start')
                <div class="tab-pane fade show active" id="profile-content" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="card">
                        <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4 mb-3">
                            <div class="card-header border-bottom-0">
                                <b class="text-sm font-weight-600">{{ trans('general.name') }}</b> <a class="float-right text-xs">{{ $employee->contact->name }}</a>
                            </div>
                            <div class="card-header border-bottom-0">
                                <b class="text-sm font-weight-600">{{ trans('employees::employees.birth_day') }}</b> <a class="float-right text-xs">{{ $employee->birth_day }}</a>
                            </div>
                            <div class="card-header border-bottom-0">
                                <b class="text-sm font-weight-600">{{ trans_choice('employees::general.positions', 1) }}</b> <a class="float-right text-xs">{{ $employee->position->name }}</a>
                            </div>
                            <div class="card-header border-bottom-0">
                                <b class="text-sm font-weight-600">{{ trans('general.email') }}</b> <a class="float-right text-xs">{{ $employee->contact->email }}</a>
                            </div>
                            <a href="{{ route('employees.employees.edit', $employee->id) }}" class="btn btn-default btn-block edit-sv">
                                <i class="fas fa-edit"></i><b>{{ trans('general.edit') }}</b>
                            </a>
                        </div>
                    </div>

                    <div class="row align-items-center0">
                        @php($attachment = $employee->attachment ?: [])
                        @foreach ($attachment as $file)
                            <div class="col-xs-12 col-sm-4 mb-4">
                                @include('partials.media.file')
                            </div>
                        @endforeach
                    </div>
                </div>
                @stack('employee_profile_content_end')
            </div>
        </div>
    </div>
    @stack('employee_content_end')
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Employees/Resources/assets/js/employees/show.min.js?v=' . module_version('employees')) }}"></script>
@endpush
