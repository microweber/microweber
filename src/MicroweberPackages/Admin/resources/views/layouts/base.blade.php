@include('admin::layouts.partials.header',[
    'disableTopBar' => true,
    'disableNavBar' => true,
])

@hasSection('content')
    <main class="module-main-holder col-lg-7">
        @yield('content' )
    </main>
@endif


@include('admin::layouts.partials.footer')
