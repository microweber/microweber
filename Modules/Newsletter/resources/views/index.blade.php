@extends('modules.newsletter::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('modules.newsletter.name') !!}</p>
@endsection
