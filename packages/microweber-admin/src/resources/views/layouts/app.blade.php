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

{{-- A11y skip link — first focusable element so keyboard users can
     jump past the global header/topbar straight to the page content
     (WCAG 2.4.1 Bypass Blocks). Visually hidden until focused. --}}
<a href="#admin-side-content"
   class="mw-skip-to-main visually-hidden-focusable position-absolute start-0 top-0 m-2 p-2 bg-primary text-white rounded shadow"
   style="z-index:2147483647;">{{ _e('Skip to main content', true) }}</a>

@hasSection('content')

     <main class="module-main-holder page-wrapper px-3" id="admin-side-content" tabindex="-1">

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

