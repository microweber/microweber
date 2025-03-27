@extends('modules.log::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('modules.log.name') !!}</p>
@endsection
