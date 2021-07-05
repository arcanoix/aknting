{!! Form::model($pay_item, [
    'id' => 'edit_payitem_form',
    'method' => 'PATCH',
    'route' => ['payroll.settings.pay-item.update', $pay_item->id],
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">

        {{ Form::selectGroup('pay_type', trans('payroll::settings.pay_type'), 'bars', $pay_types, $pay_item->pay_type) }}

        {{ Form::textGroup('pay_item', trans('payroll::settings.pay_item'), 'bars', ['required' => 'required']) }}

        {{ Form::selectGroup('amount_type', trans('payroll::settings.amount_type'), 'bars', $amount_types, $pay_item->amount_type) }}

    </div>
{!! Form::close() !!}
