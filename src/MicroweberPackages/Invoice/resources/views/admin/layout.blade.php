<div class="card style-1 mb-3">
    <div class="card-header">
        <h5>
            @yield('icon')
            <strong>@yield('title')</strong>
        </h5>
    </div>

    <style>
        .table thead th {
            text-transform: uppercase;
            font-size: 13px;
        }
    </style>

    <div class="card-body pt-3">
        @yield('content')
    </div>
</div>