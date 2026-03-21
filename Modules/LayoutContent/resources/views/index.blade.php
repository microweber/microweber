@extends('modules.layout_content::layouts.master')

@section('content')
    <h2>Hello World</h2>

    <p>Module: {!! config('modules.layout_content.name') !!}</p>
@endsection
