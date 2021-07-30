<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card bg-gradient-info card-stats">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="text-uppercase text-white mb-0">{{ $class->model->name }}</h5>
                    @if(!$contact)
                        <div class="font-weight-bold text-danger mb-0">
                            {{ trans('employees::employees.messages.contact_missing') }}
                        </div>
                    @else
                        <div class="font-weight-bold text-white mb-0">
                            {{ $contact->name }}
                        </div>
                        <div class="font-weight-bold text-white mb-0">
                            {{ $contact->email }}
                        </div>
                        <div class="font-weight-bold text-white mb-0">
                            {{ $contact->employee->position->name }}
                        </div>
                    @endif
                </div>

                <div class="col-auto">
                    <div class="icon icon-shape bg-white text-info rounded-circle shadow">
                        <i class="far fa-user"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
