@extends('admin::layouts.app')

@section('topbar2-links-right')
    @include('content::admin.content.topbar-parts.links-right')
@endsection

@section('topbar2-links-left')
    @include('content::admin.content.topbar-parts.links-left')
@endsection

@section('topbar2-links-right___')

        <a href="<?php echo route('admin.page.create'); ?>"
           class="btn btn-outline-dark"
        >
            <svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg"  width="24" height="24" viewBox="0 96 960 960"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg>
            {{"New page"}}
        </a>

    @php
        $past_page = site_url();
    @endphp

    @if (user_can_access('module.content.edit'))
        <li>
            <a href="{{$past_page}}?editmode=y"
               class="btn btn-outline-dark">
                <img height="24" width="24" src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/live-edit-button.svg" alt="">
                <span class="  ms-2" style="font-size: 14px; font-weight: bold;"><?php _e('EDIT'); ?></span>
            </a>
        </li>
    @endif

        <button id="js-admin-save-content-main-btn" type="submit" class="js-bottom-save btn btn-outline-dark" form="quickform-edit-content"><span>
        <svg fill="currentColor" class="mw-admin-save-btn-svg me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 -960 960 960"><path d="M840-683v503q0 24-18 42t-42 18H180q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h503l157 157Zm-60 27L656-780H180v600h600v-476ZM479.765-245Q523-245 553.5-275.265q30.5-30.264 30.5-73.5Q584-392 553.735-422.5q-30.264-30.5-73.5-30.5Q437-453 406.5-422.735q-30.5 30.264-30.5 73.5Q376-306 406.265-275.5q30.264 30.5 73.5 30.5ZM233-584h358v-143H233v143Zm-53-72v476-600 124Z"/></svg>

        <span class="mw-admin-save-btn-loading spinner-border spinner-border-sm me-2" style="display: none;" role="status"></span>

         <?php _e('SAVE'); ?>

           </span>
        </button>


@endsection

@section('content')

    @if(isset($recommended_content_id) and isset($recommended_category_id) and $content_id == 0)
        <module type="content/edit" content_id="{{$content_id}}" content_type="page"
                parent="{{$recommended_content_id}}" id="main-content-edit-admin"
                category="{{$recommended_category_id}}"   />
    @else
        <module type="content/edit" content_id="{{$content_id}}" content_type="page"  id="main-content-edit-admin" />
    @endif

@endsection
