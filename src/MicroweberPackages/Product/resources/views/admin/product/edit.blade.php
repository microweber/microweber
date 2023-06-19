@extends('admin::layouts.app')


@section('topbar2-links-left')

    <div class="mw-toolbar-back-button-wrapper">
        <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3 d-flex align-items-center" id="mw-modules-toolbar" style="">

            <div>
                <a href="{{route('admin.product.index')}}">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"></path></svg>
                </a>
            </div>

            <div style="margin-right: 22px">
                <h2>Edit Product
                    @if($content_id > 0)
                    / {{ content_title($content_id) }}
                    @endif
                </h2>
            </div>

            <div>
                <a href="{{route('admin.product.create')}}" class="btn btn-light w-full">
                    Add new Product
                </a>
            </div>
        </div>
    </div>

@endsection

@section('content')

@if(isset($recommended_content_id) and isset($recommended_category_id) and $content_id == 0)
    <module type="content/edit" content_id="{{$content_id}}" content_type="product"
            parent="{{$recommended_content_id}}" id="main-content-edit-admin"
            category="{{$recommended_category_id}}"   />
@else
    <module type="content/edit" content_id="{{$content_id}}" content_type="product"  id="main-content-edit-admin" />
@endif


@endsection
