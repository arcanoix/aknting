{!! Form::model($benefit, [
    'method' => 'PATCH',
    'id' => 'edit_benefit_form',
    'url' => 'payroll/modals/employees/benefit/' . $benefit->id . '/update',
    'role' => 'form',
    'class' => 'form-loading-button'
]) !!}
    <div class="row">
        {{ Form::selectGroup('type', trans_choice('general.types',1), 'id-card', $type, $benefit->type) }}

        {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'currency' => $currency, 'autofocus' => 'autofocus'], $benefit->amount) }}

        {{ Form::selectGroup('recurring',  trans('payroll::general.recurring'), 'id-card', $recurring, $benefit->recurring) }}

        {{ Form::textareaGroup('description', trans('general.description')) }}
    </div>
{!! Form::close() !!}




