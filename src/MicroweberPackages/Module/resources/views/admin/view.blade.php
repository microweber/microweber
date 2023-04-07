@extends('admin::layouts.app')

@section('content')

<div id="module-admin-wrapper" class="px-5">
    <module type="{{$type}}" view="admin" />
</div>



@endsection
