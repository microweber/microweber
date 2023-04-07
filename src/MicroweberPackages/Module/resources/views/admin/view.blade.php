@extends('admin::layouts.app')

@section('content')

<div id="module-admin-wrapper">
    <module type="{{$type}}" view="admin" />
</div>



@endsection
