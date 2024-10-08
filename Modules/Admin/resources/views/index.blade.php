@extends('modules.admin::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('modules.admin.name') !!}</p>
@endsection
