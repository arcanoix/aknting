@extends('layouts.admin')

@section('title', trans('print-template::general.title'))

@section('new_button')
<span class="new-button"><a href="{{ route('print-template.settings.create') }}" class="btn btn-success btn-sm"><spanclass="fa fa-plus"></spanclass=> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Default card -->
        <div class="card card-success">
            <div class="card-header with-border">
                <h3 class="card-title">{{ trans('print-template::general.header_list') }}</h3>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div id="delete-loading"></div>
                <div class="table-responsive">
                    <table class="table table-flush table-hover" id="tbl-items">
                        <thead class="thead-light">
                            <tr class="row table-head-line">
                                <th class="col-md-3 d-none d-sm-block">{{ trans('general.name') }}</th>
                                <th class="col-md-3 d-none d-sm-block">{{ trans_choice('general.types',0) }}</th>
                                <th class="col-md-3 d-none d-sm-block">{{ trans_choice('general.statuses',0) }}</th>
                                <th class="col-md-3 text-center">{{ trans('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($templates))
                            @foreach($templates as $template)
                            <tr id="method-{{ $template->id }}" class="row align-items-center border-top-1">
                                <td class="col-md-3 d-none d-sm-block"><a
                                        href="{{ route('print-template.settings.show', $template->id) }}">{{ $template->name }}</a>
                                </td>
                                <td class="col-md-3 d-none d-sm-block">
                                    {{ trans('print-template::sablon.type.' . $template->type) }}

                                </td>
                                <td class="col-md-3 d-none d-sm-block">
                                    @if ($template->enabled)
                                    <span class="badge badge-pill badge-success">{{ trans('general.enabled') }}</span>
                                    @else
                                    <span class="badge badge-pill badge-danger">{{ trans('general.disabled') }}</span>
                                    @endif
                                </td>
                                <td class="col-md-3 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#"
                                            role="button" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item"
                                                href="{{ route('print-template.settings.show' , $template->id) }}">{{ trans('general.show') }}</a>

                                            <a class="dropdown-item"
                                                href="{{ route('print-template.settings.edit' , $template->id ) }}">{{ trans('general.edit') }}</a>

                                            @if ($template->enabled)
                                            <a class="dropdown-item"
                                                href="{{ route('print-template.settings.disable' , $template->id ) }}">{{ trans('general.disable') }}</a>
                                            @else
                                            <a class="dropdown-item"
                                                href="{{ route('print-template.settings.enable' , $template->id ) }}">{{ trans('general.enable') }}</a>
                                            @endif

                                            <div class="dropdown-divider"></div>

                                            {!! Form::deleteLink($template,'print-template.settings.delete','print-template::general.title') !!}

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4">
                                    <a
                                        href="{{ route('print-template.settings.create') }}">{{ trans('print-template::general.list_empty') }}</a>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection

@push('scripts_start')
<script src="{{ asset('modules/PrintTemplate/Resources/assets/js/print-template.min.js?v=' . version('short')) }}">
</script>
@endpush