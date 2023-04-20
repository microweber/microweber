@extends('admin::layouts.app')

@section('topbar2-links-left')
    NIKI_VLEZ_TUKA_LEFT
@endsection

@section('topbar2-links-right')
    NIKI_VLEZ_TUKA_RIGHT
@endsection

@section('content')

<div class="col-xl-9 col-md-10 col-12 px-md-0 px-2 mx-auto">

    <module type="settings/group/website_group" />

    <?php  if (get_option('shop_disabled', 'website') != 'y') { ?>
    <module type="shop/settings" />
    <?php } ?>
</div>
@endsection
