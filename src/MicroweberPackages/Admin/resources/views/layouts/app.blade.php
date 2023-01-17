@include('admin::layouts.partials.header')

@hasSection('content')
    @yield('content' )
@endif


@include('admin::layouts.partials.footer')

