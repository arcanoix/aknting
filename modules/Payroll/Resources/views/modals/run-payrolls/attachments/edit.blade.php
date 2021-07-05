{!! Form::open([
    'id' => 'run-payroll',
    'route' => ['payroll.pay-calendars.run-payrolls.attachments.update', $payCalendar->id, $runPayroll->id],
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="card card-default">
        <div class="card-header with-border">
            <h3 class="mb-0">{{ trans_choice('payroll::run-payrolls.attachments', 2) }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    {{ Form::fileGroup(
                        'attachment',
                        '',
                        '',
                        [
                            'dropzone-class' => 'w-100',
                            'multiple' => 'multiple',
                            'options' => ['acceptedFiles' => $file_types]
                        ],
                        !empty($runPayroll) ? $runPayroll->attachment : null,
                        'col-md-12'
                    ) }}
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col-12 text-right">
                {!! Form::button(
                    '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-check"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text">' . trans('general.save') . '</span>',
                    [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success button-submit header-button-top', 'data-loading-text' => trans('general.loading')]) !!}
            </div>
        </div>
    </div>
{!! Form::close() !!}
