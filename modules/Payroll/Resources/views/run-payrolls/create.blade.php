@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans('payroll::general.wizard.run_payroll')]))

@section('content')
    <payroll-run-payroll
        :first-path="'{{ route('payroll.pay-calendars.run-payrolls.employees.create', $pay_calendar->id) }}'"
        :pay-calendar-id="{{ $pay_calendar->id }}"
        :start-step="0"
        :steps="{{ json_encode($steps) }}">
    </payroll-run-payroll>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/run-payrolls.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
