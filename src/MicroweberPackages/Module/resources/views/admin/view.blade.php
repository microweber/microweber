@extends('admin::layouts.app')

@section('content')

<div id="module-admin-wrapper" class="px-5">
    <div class="card col-xxl-8 col-xl-10 col-12 mx-auto">
        <div class="card-body">
            <div class="row">
                <module type="{{$type}}" view="admin" />
            </div>
        </div>
    </div>
</div>



@endsection
