<div class="position-relative">
    <div class="main-toolbar" id="mw-modules-toolbar">
        <a href="javascript:;" onClick="mw.admin.back()" class="btn btn-link text-silver"><i class="mdi mdi-chevron-left"></i> <?php _e("Back"); ?></a>

        <module type="admin/settings_search"/>
    </div>
</div>

<div class="card style-1 bg-light mb-3">
    <div class="card-header">
        <h5>
            @yield('icon')
            <strong>@yield('title')</strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        @yield('content')
    </div>
</div>

@yield('order_content')
