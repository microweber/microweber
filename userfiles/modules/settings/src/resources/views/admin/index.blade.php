@extends('admin::layouts.app')

@section('content')
<div class="col-lg-8 px-md-0 px-2 mx-auto">
    <h1 class="main-pages-title">Settings</h1>

    <module type="settings/group/website_group" />

    <?php  if (get_option('shop_disabled', 'website') != 'y') { ?>
    <module type="shop/settings" />
    <?php } ?>
</div>
@endsection
