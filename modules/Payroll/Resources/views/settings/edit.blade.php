@extends('layouts.admin')

@section('title', trans('payroll::general.setting'))

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="nav-wrapper">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#advanced" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class= "mr-2"></i>{{ trans('payroll::settings.run_payroll_advanced') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#pay_item" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="mr-2"></i>{{ trans('payroll::settings.pay_item') }}</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane active" id="advanced">
                    {!! Form::open([
                        'id' => 'setting',
                        'route' => 'payroll.settings.update',
                        '@submit.prevent' => 'onSubmit',
                        '@keydown' => 'form.errors.clear($event.target.name)',
                        'files' => true,
                        'role' => 'form',
                        'class' => 'form-loading-button',
                        'novalidate' => true
                    ]) !!}
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('run_payroll_prefix', trans('settings.invoice.prefix'), 'font', ['required' => 'required'], old('run_payroll_prefix', setting('payroll.run_payroll_prefix'))) }}

                                {{ Form::textGroup('run_payroll_digit', trans('settings.invoice.digit'), 'text-width', ['required' => 'required'], old('run_payroll_digit', setting('payroll.run_payroll_digit'))) }}

                                {{ Form::textGroup('run_payroll_next', trans('settings.invoice.next'), 'chevron-right', ['required' => 'required'], old('run_payroll_next', setting('payroll.run_payroll_next'))) }}

                                {{ Form::selectGroup('account', trans_choice('general.accounts', 1), 'university', $accounts, old('account', setting('payroll.account')), ['required' => 'required']) }}

                                {{ Form::selectAddNewGroup('category', trans_choice('general.categories', 1), 'folder', $categories, setting('payroll.category'), ['required' => 'required', 'path' => route('modals.categories.create') . '?type=expense']) }}

                                {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, old('payment_method', setting('payroll.payment_method')), ['required' => 'required']) }}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row save-buttons">
                                {{ Form::saveButtons('settings.index') }}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="tab-pane" id="pay_item">
                    <div class="card shadow">
                        <div class="card-header border-bottom-0">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="button" @click="onPayitem('{{ trans('payroll::settings.pay_item') }}')" id="add-company" class="btn btn-success"> {{ trans('payroll::general.add_new', ['type' => trans('payroll::settings.pay_item')]) }}</button>
                                </div>
                            </div>
                        </div>
                        @include('payroll::settings.pay-item-index')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('content_content_end')
    <component v-bind:is="add_pay_item_html"></component>
    <component v-bind:is="edit_pay_item_html"></component>
@endpush


@push('scripts_start')
    <script src="{{ asset('modules/Payroll/Resources/assets/js/payroll-settings.min.js?v=' . module_version('payroll')) }}"></script>
@endpush


