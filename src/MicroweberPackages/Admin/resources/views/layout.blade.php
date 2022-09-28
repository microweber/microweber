<div class="position-relative">
    <div class="main-toolbar" id="mw-modules-toolbar">
        <a href="javascript:;" onClick="mw.admin.back()" class="btn btn-link text-silver px-0"><i class="mdi mdi-chevron-left"></i> <?php _e("Back"); ?></a>

        <module type="admin/settings_search"/>
    </div>
</div>

<div class="card @yield('card-style') style-1 mb-3">
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
