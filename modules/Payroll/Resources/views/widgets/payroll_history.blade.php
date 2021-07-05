<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="card-body" id="widget-line-{{ $class->model->id }}">
            <div class="chart">
                {!! $chart->container() !!}
            </div>
        </div>
    </div>
</div>

@push('charts')
    <script>
        var widget_line_{{ $model->id }} = new Vue({
            el: '#widget-line-{{ $model->id }}',
        });
    </script>
@endpush

@push('body_scripts')
    {!! $chart->script() !!}
@endpush
