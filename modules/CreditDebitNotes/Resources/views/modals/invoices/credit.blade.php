{!! Form::open([
    'id' => 'form-credit-transaction',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'route' => ['credit-debit-notes.modals.invoices.invoice.credits-transactions.store', $invoice->id],
    'novalidate' => true
]) !!}
<base-alert type="warning" v-if="typeof form.response !== 'undefined' && form.response.error" v-html="form.response.message"></base-alert>

<div class="row">
    {{ Form::textGroup('currency', trans_choice('general.currencies', 1), 'exchange-alt', ['disabled' => 'true'], $currencies[$invoice->currency_code]) }}

    {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'autofocus' => 'autofocus', 'currency' => $currency, 'dynamic-currency' => 'currency'], $invoice->grand_total) }}

    {{ Form::textareaGroup('description', trans('general.description'), '', null, ['rows' => '3']) }}

    {!! Form::hidden('document_id', $invoice->id, ['id' => 'invoice_id', 'class' => 'form-control', 'required' => 'required']) !!}
    {!! Form::hidden('category_id', $invoice->category->id, ['id' => 'category_id', 'class' => 'form-control', 'required' => 'required']) !!}
    {!! Form::hidden('amount', $invoice->grand_total, ['id' => 'amount', 'class' => 'form-control', 'required' => 'required']) !!}
    {!! Form::hidden('currency_code', $invoice->currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
    {!! Form::hidden('currency_rate', $invoice->currency_rate, ['id' => 'currency_rate', 'class' => 'form-control', 'required' => 'required']) !!}

    {!! Form::hidden('type', 'expense') !!}
</div>
{!! Form::close() !!}
