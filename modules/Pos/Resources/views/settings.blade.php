@extends('layouts.admin')

@section('title', trans('pos::general.name'))

@section('content')
    {!! Form::open([
        'id' => 'setting',
        'method' => 'PATCH',
        'route' => 'pos.settings.update',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true,
    ]) !!}
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">{{ trans('pos::settings.order.order_numbering') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('number_prefix', trans('pos::settings.order.prefix'), 'font', ['required' => 'required'], setting('pos.pos_order.number_prefix')) }}

                {{ Form::textGroup('number_digit', trans('pos::settings.order.digit'), 'text-width', ['required' => 'required'], setting('pos.pos_order.number_digit')) }}

                {{ Form::textGroup('number_next', trans('pos::settings.order.next'), 'chevron-right', ['required' => 'required'], setting('pos.pos_order.number_next')) }}
            </div>
        </div>

        <div class="card-header">
            <h3 class="mb-0">{{ trans('pos::settings.cash.payments_by_cash') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                {{ Form::selectRemoteGroup(
                    'cash_account_id',
                    trans('pos::settings.general.account'),
                    'university',
                    $accounts,
                    setting('pos.general.cash_account_id'),
                    ['remote_action' => route('accounts.index'), 'required' => 'required']
                ) }}

                {{ Form::selectGroup(
                    'cash_payment_method',
                    trans_choice('general.payment_methods', 1),
                    'money-bill-alt',
                    $payment_methods,
                    setting('pos.general.cash_payment_method'),
                    ['required' => 'required']
                ) }}
            </div>
        </div>

        <div class="card-header">
            <h3 class="mb-0">{{ trans('pos::settings.card.payments_by_card') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                {{ Form::selectRemoteGroup(
                    'card_account_id',
                    trans('pos::settings.general.account'),
                    'university',
                    $accounts,
                    setting('pos.general.card_account_id'),
                    ['remote_action' => route('accounts.index'), 'required' => 'required']
                ) }}

                {{ Form::selectGroup(
                    'card_payment_method',
                    trans_choice('general.payment_methods', 1),
                    'credit-card',
                    $payment_methods,
                    setting('pos.general.card_payment_method'),
                    ['required' => 'required']
                ) }}
            </div>
        </div>

        <div class="card-header">
            <h3 class="mb-0">{{ trans('pos::settings.general.other') }}</h3>
        </div>

        <div class="card-body">
            <div class="row">
                {{ Form::selectRemoteGroup(
                    'guest_customer_id',
                    trans('pos::settings.general.guest_customer'),
                    'user',
                    $customers,
                    setting('pos.general.guest_customer_id'),
                    ['remote_action' => route('customers.index'), 'required' => 'required']
                ) }}

                {{ Form::selectRemoteGroup(
                    'sale_category_id',
                    trans('pos::settings.general.sale_category'),
                    'folder',
                    $income_categories,
                    setting('pos.general.sale_category_id'),
                    ['remote_action' => route('categories.index') . '?search=type:income', 'required' => 'required']
                ) }}

                {{ Form::selectRemoteGroup(
                    'change_category_id',
                    trans('pos::settings.general.change_category'),
                    'folder',
                    $expense_categories,
                    setting('pos.general.change_category_id'),
                    ['remote_action' => route('categories.index') . '?search=type:expense', 'required' => 'required']
                ) }}

                {{ Form::selectGroup(
                    'printer_paper_size',
                    trans('pos::settings.general.printer_paper_size'),
                    'text-width',
                    ['57' => '57mm (2 1/4")', '80' => '80mm (3 1/8")'],
                    setting('pos.general.printer_paper_size'),
                    ['required' => 'required']
                ) }}

                {{ Form::radioGroup('use_paper_cutter', trans('pos::settings.general.use_paper_cutter'), setting('pos.general.use_paper_cutter')) }}

                {{ Form::radioGroup('show_logo_in_receipt', trans('pos::settings.general.show_logo_in_receipt'), setting('pos.general.show_logo_in_receipt')) }}

                {{ Form::radioGroup('use_barcode_scanner', trans('pos::settings.general.use_barcode_scanner'), setting('pos.general.use_barcode_scanner')) }}
            </div>
        </div>

        @permission('update-pos-settings')
        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::saveButtons('settings.index') }}
            </div>
        </div>
        @endpermission
    </div>

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/Pos/Resources/assets/js/settings.min.js?v=' . module_version('pos')) }}"></script>
@endpush
