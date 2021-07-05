@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans('payroll::general.wizard.run_payroll')]))

@section('content')
    <payroll-run-payroll
        :first-path="'{{ route('payroll.run-payrolls.employees.edit', $run_payroll->id) }}'"
        :pay-calendar-id="{{ $run_payroll->pay_calendar_id }}"
        :start-step="0"
        :steps="{{ json_encode($steps) }}">
    </payroll-run-payroll>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/run-payrolls.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
