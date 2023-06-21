@extends('admin::layouts.app')


@section('topbar2-links-right')


    <li>
        <a href="<?php echo route('admin.product.create'); ?>"
           class="btn btn-outline-dark border-1 rounded-circle p-2" style="height: 40px; width: 45px;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="New product">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg"  width="24" height="24" viewBox="0 96 960 960"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg>
        </a>
    </li>



    @php
        $past_page = site_url();
    @endphp

    @if (user_can_access('module.content.edit'))
        <li>
        <a href="{{$past_page}}?editmode=y"
           class="btn btn-outline-dark">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="24" viewBox="0 96 960 960" width="24"><path d="M480.078 729.333q72.255 0 122.755-50.578 50.5-50.579 50.5-122.833 0-72.255-50.578-122.755-50.579-50.5-122.833-50.5-72.255 0-122.755 50.578-50.5 50.579-50.5 122.833 0 72.255 50.578 122.755 50.579 50.5 122.833 50.5Zm-.235-62.666q-46.176 0-78.343-32.324-32.167-32.323-32.167-78.5 0-46.176 32.324-78.343 32.323-32.167 78.5-32.167 46.176 0 78.343 32.324 32.167 32.323 32.167 78.5 0 46.176-32.324 78.343-32.323 32.167-78.5 32.167ZM480 856q-146 0-264.667-82.5Q96.667 691 40 556q56.667-135 175.333-217.5Q334 256 480 256q146 0 264.667 82.5Q863.333 421 920 556q-56.667 135-175.333 217.5Q626 856 480 856Zm0-300Zm-.112 233.334q118.445 0 217.612-63.5Q796.667 662.333 848.667 556q-52-106.333-151.054-169.834-99.055-63.5-217.501-63.5-118.445 0-217.612 63.5Q163.333 449.667 110.666 556q52.667 106.333 151.721 169.834 99.055 63.5 217.501 63.5Z"/></svg>
            <span class="ms-2" style="font-size: 14px; font-weight: bold;"><?php _e('EDIT'); ?></span>
        </a>
        </li>
    @endif

    <li>
        <button id="js-admin-save-content-main-btn" type="submit" class="js-bottom-save btn btn-outline-dark" form="quickform-edit-content"><span>
        <svg fill="currentColor" class="mw-admin-save-btn-svg me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 -960 960 960"><path d="M840-683v503q0 24-18 42t-42 18H180q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h503l157 157Zm-60 27L656-780H180v600h600v-476ZM479.765-245Q523-245 553.5-275.265q30.5-30.264 30.5-73.5Q584-392 553.735-422.5q-30.264-30.5-73.5-30.5Q437-453 406.5-422.735q-30.5 30.264-30.5 73.5Q376-306 406.265-275.5q30.264 30.5 73.5 30.5ZM233-584h358v-143H233v143Zm-53-72v476-600 124Z"/></svg>

        <span class="mw-admin-save-btn-loading spinner-border spinner-border-sm me-2" style="display: none;" role="status"></span>

         <?php _e('SAVE'); ?>

           </span>
        </button>
    </li>


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
