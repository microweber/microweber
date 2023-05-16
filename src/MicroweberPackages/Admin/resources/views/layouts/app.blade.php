@php


if(!isset($options)){
 $options = [];
}
if(isset($_GET['iframe']) and $_GET['iframe']){
    $options['disableNavBar'] = true;
    $options['disableTopBar'] = true;
    $options['iframe'] = true;
}

@endphp


@include('admin::layouts.partials.header',$options)

@hasSection('content')
    <main class="module-main-holder page-wrapper overflow-hidden">

        @if(!isset($options['disableTopBar']))
          @include('admin::layouts.partials.topbar2')
        @endif
       <div class="page-body  @if(isset($options['iframe'])) page-body-iframe @endif"  >
           @yield('content')
       </div>

            @include('admin::layouts.partials.copyright')
    </main>
@endif


@include('admin::layouts.partials.footer')

