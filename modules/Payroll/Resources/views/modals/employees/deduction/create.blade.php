{!! Form::open([
    'route' => ['payroll.modals.employees.deduction.store', $employee->id],
    'id' => 'new_deduction_form',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::selectGroup('type', trans_choice('general.types',1), 'id-card', $type) }}

        {{ Form::moneyGroup('amount', trans('general.amount'), 'money-bill-alt', ['required' => 'required', 'autofocus' => 'autofocus', 'currency' => $currency], 0) }}

        {{ Form::selectGroup('recurring',  trans('payroll::general.recurring'), 'id-card', $recurring) }}

        {{ Form::textGroup('from_date', trans('payroll::general.from_date'), 'calendar', []) }}

        {{ Form::textGroup('to_date', trans('payroll::general.to_date'), 'calendar', []) }}

        {{ Form::textareaGroup('description', trans('general.description')) }}

        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
    </div>
{!! Form::close() !!}


