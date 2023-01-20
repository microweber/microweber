@extends('admin::layouts.app')

@section('content')

    <module type="content/edit" content_id="{{$content_id}}" content_type="product" />

@endsection
