@include('admin::layouts.partials.header')

@hasSection('content')
    <main class="module-main-holder col-lg-7">
    @yield('content' )
    </main>
@endif


@include('admin::layouts.partials.footer')

