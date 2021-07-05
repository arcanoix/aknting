{!! Form::open([
    'id' => 'form-create-position',
    'route' => 'employees.modals.positions.store',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button needs-validation',
    'novalidate' => 'true'
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'font') }}

        {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
    </div>
{!! Form::close() !!}
