@extends('admin::layouts.app')

@section('topbar2-links-left')

    <div class="mw-toolbar-back-button-wrapper">
        <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3 " id="mw-modules-toolbar" style="">
            <a href="{{route('admin.category.index')}}">
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"></path></svg>
            </a>
        </div>
    </div>

@endsection


@section('content')

    <div class="d-flex">

    <script>
        mw.require('content.js', true);
    </script>

    <div class="module-content w-75 pe-3 mx-auto">
        <div class="row">
            <div class="col-md-12">
                @php
                    $isShopAttribute = 0;
                    if (isset($isShop)) {
                         $isShopAttribute = 1;
                    }
                @endphp

                @if($isShopAttribute == 1)
                    <h3> Add category to Shop</h3>
                @else
                   <h3> Add category to Website</h3>
                @endif

                <module type="categories/edit_category" is_shop="{{$isShopAttribute}}" id="admin-category-edit" data-category-id="{{$id}}"  />
            </div>
        </div>
    </div>

</div>
@endsection
