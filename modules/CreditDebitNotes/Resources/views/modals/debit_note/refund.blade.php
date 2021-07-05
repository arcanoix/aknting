{!! Form::open([
    'id' => 'form-refund-transaction',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => ['credit-debit-notes.modals.debit-notes.debit-note.refund-transactions.store', $debit_note->id],
    'novalidate' => true
]) !!}
<base-alert type="warning" v-if="typeof form.response !== 'undefined' && form.response.error" v-html="form.response.message"></base-alert>

<div class="row">
    {{ Form::dateGroup('paid_at', trans('general.date'), 'calendar', ['id' => 'paid_at', 'required' => 'required', 'show-date-format' => company_date_format(), 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], Date::now()->toDateString()) }}

    {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'autofocus' => 'autofocus', 'currency' => $currency, 'dynamic-currency' => 'currency'], $debit_note->amount) }}

    {{ Form::selectGroup('account_id', trans_choice('general.accounts', 1), 'university', $accounts, setting('default.account'), ['required' => 'required', 'change' => 'onChangePaymentAccount']) }}

    {{ Form::textGroup('currency', trans_choice('general.currencies', 1), 'exchange-alt', ['disabled' => 'true'], $currencies[$debit_note->currency_code]) }}

    {{ Form::textareaGroup('description', trans('general.description'), '', null, ['rows' => '3']) }}

    {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, setting('default.payment_method'), ['required' => 'requied']) }}

    {{ Form::selectAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, null, ['required' => 'required', 'path' => route('modals.categories.create') . '?type=income']) }}

    {!! Form::hidden('document_id', $debit_note->id, ['id' => 'invoice_id', 'class' => 'form-control', 'required' => 'required']) !!}
    {!! Form::hidden('amount', $debit_note->amount, ['id' => 'amount', 'class' => 'form-control', 'required' => 'required']) !!}
    {!! Form::hidden('currency_code', $debit_note->currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
    {!! Form::hidden('currency_rate', $debit_note->currency_rate, ['id' => 'currency_rate', 'class' => 'form-control', 'required' => 'required']) !!}

    {!! Form::hidden('type', 'debit_note_refund') !!}
</div>
{!! Form::close() !!}
