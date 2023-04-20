@extends('admin::layouts.app')

@if(isset($_GET['group']))

    @section('topbar2-links-left')

        @php
            $backButtonUrl = '';
            if (isset($params['history_back'])) {
                $backButtonUrl = 'javascript:mw.admin.back();';
            } elseif (isset($params['back_button_url'])) {
                $backButtonUrl = admin_url() . $params['back_button_url'];
            } else {
                $backButtonUrl = admin_url() . 'view:modules';
            }
        @endphp

        <a href="<?php print $backButtonUrl ?>">
            <svg xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"/></svg>
            <?php $active = mw()->url_manager->param('view'); ?>

        </a>

    @endsection

    @section('topbar2-links-right')

    @endsection

@endif

@section('content')

<div class="col-xl-9 col-md-10 col-12 px-md-0 px-2 mx-auto">
    <module type="settings/group/website_group" />

    <?php  if (get_option('shop_disabled', 'website') != 'y') { ?>
    <module type="shop/settings" />
    <?php } ?>
</div>
@endsection
