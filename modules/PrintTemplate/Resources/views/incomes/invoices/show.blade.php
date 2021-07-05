<div class="dropup header-drop-top">
    <button type="button" class="btn btn-white btn-sm" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-print"></i>&nbsp; {{ trans('print-template::general.title') }}
    </button>
    <div class="dropdown-menu" role="menu">
        @if (count($templates))
        @foreach($templates as $template)
        <a class="dropdown-item" target="_blank"
            href="{{ route('print-template.settings.print' , ['print_template'=>$template->id ,'invoice'=> $invoice_id] ) }}"
            title="{{ $template->name . ' ' .$template->pagesize }}">{{ $template->name }}</a>
        @endforeach
        @else
        <a class="dropdown-item" href="{{ route('print-template.settings.create' ) }}"
            title="{{ trans("print-template::general.header_create") }}">{{ trans("print-template::general.header_create") }}</a>
        @endif
    </div>
</div>