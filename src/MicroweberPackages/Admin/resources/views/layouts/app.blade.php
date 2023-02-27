@include('admin::layouts.partials.header')

@hasSection('content')
    <main class="module-main-holder">
    @yield('content' )
    </main>
@endif


@include('admin::layouts.partials.footer')

