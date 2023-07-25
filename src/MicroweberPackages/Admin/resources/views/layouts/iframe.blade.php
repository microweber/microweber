@include('admin::layouts.partials.header',[
    'disableTopBar' => true,
    'disableNavBar' => true,
    'iframeMode' => true,
])




@hasSection('content')
    <main class="module-main-holder col-lg-12">
        @yield('content' )
    </main>
@endif


@include('admin::layouts.partials.footer')
