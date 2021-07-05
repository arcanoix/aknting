@extends('layouts.admin')

@section('title', trans('print-template::general.title'))

@section('content')
<iframe src="{{ route('print-template.settings.design' , $print_template->id) }}" style="width:100%;height:2600px"
    frameborder="0"></iframe>
@endsection