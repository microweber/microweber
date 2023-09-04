@extends('admin::layouts.app')


@section('topbar2-links-right')
    @include('content::admin.content.topbar-parts.links-right')
@endsection

@section('topbar2-links-left')
    @include('content::admin.content.topbar-parts.links-left')
@endsection

@section('content')

@if(isset($recommended_content_id) and isset($recommended_category_id) and $content_id == 0)
    <module type="content/edit" content_id="{{$content_id}}"
            parent="{{$recommended_content_id}}" id="main-content-edit-admin"
            category="{{$recommended_category_id}}"   />
@else
    <module type="content/edit" content_id="{{$content_id}}"    id="main-content-edit-admin" />
@endif


@endsection
