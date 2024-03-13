@extends('admin::layouts.app')

@php
    $isShopAttribute = 0;
    if (isset($isShop)) {
         $isShopAttribute = 1;
    }
@endphp

@section('topbar2-links-left')

    <div class="d-flex align-items-center">
        <div class="mw-toolbar-back-button-wrapper">
            <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3 d-flex align-items-center" id="mw-modules-toolbar" style="">
                <div>

                    <?php if ($isShopAttribute == 1): ?>
                    <a href="{{route('admin.shop.category.index')}}">
                        <?php else : ?>
                    <a href="{{route('admin.category.index')}}">
                    <?php endif; ?>

                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"></path></svg>
                    </a>

                </div>
            </div>
        </div>

        <div class="ms-3 mb-3 d-flex align-items-center">
            <a class="tblr-body-color form-label mb-0 text-decoration-none font-weight-bold d-md-block d-none"

            <?php if ($isShopAttribute == 1): ?>
             href="{{route('admin.shop.category.index')}}"
                <?php else : ?>
             href="{{route('admin.category.index')}}"
                <?php endif; ?>

               class="mb-0">


                @if($id == 0)
                    @if($isShopAttribute == 1)
                       {{ 'Add shop category' }}
                    @else
                       {{ 'Add website category' }}
                    @endif
                @else
                    @if($isShopAttribute == 1)
                        {{ 'Edit shop category' }}
                    @else
                        {{ 'Edit website category' }}
                    @endif
                @endif

            </a>
            <span class="tblr-body-color form-label mb-0 font-weight-bold ms-1 d-lg-block d-none">
                   @if($id > 0)
                    / {{ category_title($id) }}

                    <a class="tblr-body-color" href="{{category_link($id)}}" target="_blank" data-bs-toggle="tooltip" aria-label="View" data-bs-original-title="View">

<svg xmlns="http://www.w3.org/2000/svg" height="14" viewBox="0 -960 960 960" width="14"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h560v-280h80v280q0 33-23.5 56.5T760-120H200Zm188-212-56-56 372-372H560v-80h280v280h-80v-144L388-332Z"/></svg>


                    </a>


                @endif
              </span>
        </div>
    </div>

@endsection

@section('topbar2-links-right')


    @php

          $quickContentAdd = false;

           if(isset($_GET['quickContentAdd']) and $_GET['quickContentAdd']){
              $quickContentAdd = true;
           }




    @endphp





    @if(!$quickContentAdd)
    @if($id > 0)
        @php
            if ($isShopAttribute == 1) {
                $addSubCateoryLink = route('admin.shop.category.create') . '?parent=shop&addsubcategory='.$id;
            } else {
                $addSubCateoryLink = route('admin.category.create') . '?parent=blog&addsubcategory='.$id;
            }
        @endphp
        <li>
            <a class="tblr-body-color text-decoration-none" href="{{category_link($id)}}" target="_blank"  data-bs-toggle="tooltip" aria-label="View Category" data-bs-original-title="View Category">
                <svg class="me-2 " fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="20"><path d="M480.078 729.333q72.255 0 122.755-50.578 50.5-50.579 50.5-122.833 0-72.255-50.578-122.755-50.579-50.5-122.833-50.5-72.255 0-122.755 50.578-50.5 50.579-50.5 122.833 0 72.255 50.578 122.755 50.579 50.5 122.833 50.5Zm-.235-62.666q-46.176 0-78.343-32.324-32.167-32.323-32.167-78.5 0-46.176 32.324-78.343 32.323-32.167 78.5-32.167 46.176 0 78.343 32.324 32.167 32.323 32.167 78.5 0 46.176-32.324 78.343-32.323 32.167-78.5 32.167ZM480 856q-146 0-264.667-82.5Q96.667 691 40 556q56.667-135 175.333-217.5Q334 256 480 256q146 0 264.667 82.5Q863.333 421 920 556q-56.667 135-175.333 217.5Q626 856 480 856Zm0-300Zm-.112 233.334q118.445 0 217.612-63.5Q796.667 662.333 848.667 556q-52-106.333-151.054-169.834-99.055-63.5-217.501-63.5-118.445 0-217.612 63.5Q163.333 449.667 110.666 556q52.667 106.333 151.721 169.834 99.055 63.5 217.501 63.5Z"/></svg>
            </a>
            <a href="{{$addSubCateoryLink}}" class="btn btn-outline-dark"><?php _e("Add subcategory"); ?></a> &nbsp;
        </li>
    @endif


    @if($isShopAttribute == 1)
        <li>
        <a href="<?php echo route('admin.shop.category.create')."?parent=shop"; ?>"
           class="btn btn-outline-dark"
        >
            <svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg>
            {{"New category"}}
        </a>
        </li>
    @else
    <li>
        <a href="<?php echo route('admin.category.create')."?parent=blog"; ?>"
           class="btn btn-outline-dark"
        >
            <svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg>
            {{"New category"}}
        </a>
        </li>
    @endif

    <li>
        <button  type="button" onclick="save_cat(this);" dusk="category-save" class="js-category-save btn btn-dark" form="quickform-edit-content">
            <i class="mdi mdi-content-save me-1"></i> <?php _e('Save') ?>
        </button>
    </li>


    @endif

@endsection


@section('content')

    <div class="d-flex">

    <script>
        mw.require('content.js', true);
    </script>

    <div class="module-content col-xxl-10 col-lg-11 col-12 mx-auto">
        <div class="row">
            <div class="col-md-12">
                <module type="categories/edit_category" is_shop="{{$isShopAttribute}}" id="admin-category-edit" data-category-id="{{$id}}"  />
            </div>
        </div>
    </div>

</div>
@endsection
