@extends('admin::layouts.app')


@section('topbar2-links-right')

   <div id="content-title-field-buttons" class="mw-page-component-disabled">
       <button id="js-admin-save-content-main-btn" type="submit" class="btn btn-dark mw-admin-bold-outline-dark btn-save js-bottom-save shadow-none border-0 ms-atuo me-3" form="quickform-edit-content"><span>
        <svg fill="currentColor" class="mw-admin-save-btn-svg me-2" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 -960 960 960" width="28"><path d="M840-683v503q0 24-18 42t-42 18H180q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h503l157 157Zm-60 27L656-780H180v600h600v-476ZM479.765-245Q523-245 553.5-275.265q30.5-30.264 30.5-73.5Q584-392 553.735-422.5q-30.264-30.5-73.5-30.5Q437-453 406.5-422.735q-30.5 30.264-30.5 73.5Q376-306 406.265-275.5q30.264 30.5 73.5 30.5ZM233-584h358v-143H233v143Zm-53-72v476-600 124Z"/></svg>

        <span class="mw-admin-save-btn-loading spinner-border spinner-border-sm me-2" style="display: none;" role="status"></span>

         <?php _e('SAVE'); ?>

           </span>
       </button>
   </div>

    @php
        $past_page = site_url();
    @endphp

    @if (user_can_access('module.content.edit'))
        <a href="{{$past_page}}?editmode=y"
           class="btn btn-light go-live-edit-href-set admin-toolbar-buttons shadow-none">
            <img height="28" width="28" src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/live-edit-button.svg" alt="">
            <span class="  ms-2" style="font-size: 14px; font-weight: bold;"><?php _e('EDIT'); ?></span>
        </a>
    @endif


@endsection

@section('topbar2-links-left')

   <div class="d-flex align-items-center">
       <div class="mw-toolbar-back-button-wrapper">
           <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3 d-flex align-items-center" id="mw-modules-toolbar" style="">
               <div>
                   <a href="{{route('admin.product.index')}}">
                       <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"></path></svg>
                   </a>
               </div>
           </div>
       </div>

       <div class="ms-3 mb-3 d-flex align-items-center">
           <a class="tblr-body-color form-label mb-0 text-decoration-none font-weight-bold" href="{{route('admin.product.index')}}" class="mb-0">

               @if($content_id > 0)
                   {{ "Edit Product" }}
               @else
                   {{ "Add Product" }}
               @endif
           </a>
              <span class="tblr-body-color form-label mb-0 font-weight-bold ms-1">
                   @if($content_id > 0)
                      / {{ content_title($content_id) }}
                  @endif
              </span>
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
