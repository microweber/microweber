@php
$options['disableNavBar'] = false;
$options['disableTopBar'] = false;

if (isset($disableNavBar)) {
    $options['disableNavBar'] = $disableNavBar;
}
if (isset($disableTopBar)) {
    $options['disableTopBar'] = $disableTopBar;
}
@endphp

@include('admin::layouts.partials.header',$options)

@hasSection('content')

     <main class="module-main-holder page-wrapper px-3" id="admin-side-content">

         @if ($options['disableTopBar'] == false)
         <div>
             @include('admin::layouts.partials.topbar2')
         </div>
         @endif

       <div class="@if(isset($options['iframe'])) page-body-iframe @else page-body @endif"  >
           @yield('content')
       </div>
        @if(!isset($options['disableNavBar']) or !$options['disableNavBar'])
            @include('admin::layouts.partials.copyright')
        @endif
    </main>
@endif


@include('admin::layouts.partials.footer')

