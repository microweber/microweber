@extends('admin::layouts.app')

@section('content')
<div class="mx-sm-5 mx-1">

    <div class="position-relative">
        <div class="main-toolbar" id="mw-modules-toolbar">
            <a href="javascript:;" onClick="mw.admin.back()" class="btn btn-link text-silver"><i class="mdi mdi-chevron-left"></i> <?php _e("Back"); ?></a>

            <module type="admin/settings_search"/>
        </div>
    </div>

    <div class="card bg-azure-lt mb-3">
        <div class="card-header">
            <h5>
                @yield('icon')
                <strong>@yield('title')</strong>
            </h5>
        </div>
        <div class="card-body">
            @yield('content')
        </div>
    </div>
</div>

@yield('order_content')

@endsection
