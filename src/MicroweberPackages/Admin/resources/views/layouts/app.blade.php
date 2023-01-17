@include('admin::layouts.partials.header')

<main>
@hasSection('content')
    @yield('content' )
@endif
</main>

@include('admin::layouts.partials.footer')

