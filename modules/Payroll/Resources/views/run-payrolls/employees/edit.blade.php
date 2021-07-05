@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('payroll::general.wizard.run_payroll', 1)]))

@section('content')
    <payroll-run-payroll
        :first-path="'{{ route('payroll.run-payrolls.edit', $run_payroll->id) }}'"
        :pay-calendar-id="{{ $pay_calendar->id }}"
        :start-step="0"
        :steps="{{ json_encode($steps) }}">
    </payroll-run-payroll>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/run-payrolls.min.js?v=' . module_version('payroll')) }}"></script>
@endpush
