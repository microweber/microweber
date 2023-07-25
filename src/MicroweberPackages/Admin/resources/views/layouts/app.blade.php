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

if(isset($_GET['enableTopBar']) and $_GET['enableTopBar']){
    $options['disableTopBar'] = false;
}


@endphp


@include('admin::layouts.partials.header',$options)



@hasSection('content')
    <main class="module-main-holder page-wrapper">

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

