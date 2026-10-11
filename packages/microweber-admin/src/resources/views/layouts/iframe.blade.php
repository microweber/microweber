@php
$options =[
    'disableTopBar' => true,
    'disableNavBar' => true,
    'iframeMode' => true,
];

if(isset($_GET['disableTopBar']) and $_GET['disableTopBar']){
    $options['disableTopBar'] = intval($_GET['disableTopBar']);
}
if(isset($_GET['disableNavBar']) and $_GET['disableNavBar']){
    $options['disableNavBar'] = intval($_GET['disableNavBar']);
}

if(isset($_GET['iframeMode']) and $_GET['iframeMode']){
    $options['iframeMode'] = intval($_GET['iframeMode']);
}

@endphp
@include('admin::layouts.partials.header',$options)


@hasSection('content')
    <main class="module-main-holder col-lg-12">
        @yield('content' )
    </main>
@endif


@include('admin::layouts.partials.footer')
