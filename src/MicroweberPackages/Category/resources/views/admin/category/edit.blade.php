@extends('admin::layouts.app')

@section('topbar2-links-left')

    <div class="mw-toolbar-back-button-wrapper">
        <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3 " id="mw-modules-toolbar" style="">

            @if(isset($isShop))
                <a href="{{route('admin.shop.category.index')}}">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"></path></svg>
                </a>
            @else
            <a href="{{route('admin.category.index')}}">
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"></path></svg>
            </a>

            @endif

        </div>
    </div>

@endsection


@section('content')

    <div class="d-flex">

    <script>
        mw.require('content.js', true);
    </script>

    <div class="module-content col-xxl-10 col-lg-11 col-12 mx-auto">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between aling-items-center">
                    @php
                        $isShopAttribute = 0;
                        if (isset($isShop)) {
                             $isShopAttribute = 1;
                        }
                    @endphp

                    @if($isShopAttribute == 1)
                        <h1 class="main-pages-title"> {{ 'Add category to Shop' }}</h1>
                    @else
                       <h1 class="main-pages-title"> {{ 'Add category to Website' }}</h1>
                    @endif


                    <div>
                        @if($isShopAttribute == 1)
                        <a href="<?php echo route('admin.shop.category.create')."?parent=shop"; ?>"
                           class="btn btn-outline-primary"
                        >
                            Create new category
                        </a>
                        @else
                        <a href="<?php echo route('admin.category.create')."?parent=blog"; ?>"
                           class="btn btn-outline-primary"
                        >
                            Create new category
                        </a>
                        @endif
                    </div>
                </div>


                <module type="categories/edit_category" is_shop="{{$isShopAttribute}}" id="admin-category-edit" data-category-id="{{$id}}"  />
            </div>
        </div>
    </div>

</div>
@endsection
