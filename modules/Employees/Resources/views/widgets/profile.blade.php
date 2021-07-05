<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card bg-gradient-info card-stats">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="text-uppercase text-white mb-0">{{ $class->model->name }}</h5>
                    <div class="font-weight-bold text-white mb-0">
                        {{ $employee->contact->name }}
                    </div>
                    <div class="font-weight-bold text-white mb-0">
                        {{ $employee->contact->email }}
                    </div>
                    <div class="font-weight-bold text-white mb-0">
                        {{ $employee->position->name }}
                    </div>
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
