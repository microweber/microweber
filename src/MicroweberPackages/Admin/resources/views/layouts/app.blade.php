@php


if(!isset($options)){
 $options = [];
}

if(isset($_GET['iframe']) and $_GET['iframe']){
    $isIframe = true;
}
$options['disableTopBar'] = false;
if(isset($isIframe) and $isIframe){
    $options['disableNavBar'] = true;
    $options['disableTopBar'] = true;
    $options['iframe'] = true;
}
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
   
     
     <main class="module-main-holder page-wrapper" id="admin-side-content"> 
        @if($options['disableTopBar'] == false)
            @include('admin::layouts.partials.topbar2')
        @endif
       <div class="page-body  @if(isset($options['iframe'])) page-body-iframe @endif"  >
           @yield('content')
       </div>
        @if(!isset($options['disableNavBar']))
            @include('admin::layouts.partials.copyright')
        @endif
    </main>
@endif


@include('admin::layouts.partials.footer')

