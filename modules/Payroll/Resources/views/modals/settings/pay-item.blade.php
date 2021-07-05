{!! Form::open([
    'route' => ['payroll.settings.pay-item.store', []],
    'id' => 'add_payitem_form',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::selectGroup('pay_type', trans('payroll::settings.pay_type'), 'bars', $pay_types) }}

        {{ Form::textGroup('pay_item', trans('payroll::settings.pay_item'), 'bars', ['required' => 'required']) }}

        {{ Form::selectGroup('amount_type', trans('payroll::settings.amount_type'), 'bars', $amount_types) }}
    </div>
{!! Form::close() !!}


