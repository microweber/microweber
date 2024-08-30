@extends('admin::layouts.app')

@if(isset($_GET['group']))

    @section('topbar2-links-left')

        <div class="mw-toolbar-back-button-wrapper">
            <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3 " id="mw-modules-toolbar" style="">
                <a href="{{admin_url('settings')}}">
                     <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"></path></svg>
                </a>
            </div>
        </div>

    @endsection

@endif

@section('content')

<div class="col-xxl-10 col-md-11 col-12 px-md-0 px-2 mx-auto">
    <module type="settings/group/website_group" />

    <?php  if (get_option('shop_disabled', 'website') != 'y') { ?>
    <module type="shop/settings" />
    <?php } ?>
</div>
@endsection
