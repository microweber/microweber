@extends('admin::layouts.app')

@section('content')

<div id="module-admin-wrapper" class="px-5">
    <div class="col-xxl-10 col-lg-11 col-12 mx-auto">

        <module type="{{$type}}" view="admin" />

    </div>
</div>



@endsection
