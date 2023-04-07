@extends('admin::layouts.app')

@section('content')
<div class="col-lg-8 mx-auto">
    <module type="settings/group/website_group" />

    <?php  if (get_option('shop_disabled', 'website') != 'y') { ?>
    <module type="shop/settings" />
    <?php } ?>
</div>
@endsection
