@extends('modules.layout_content::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('modules.layout_content.name') !!}</p>
@endsection
