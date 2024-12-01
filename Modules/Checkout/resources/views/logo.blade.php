@php
    $logo = get_option('logo', 'website');
@endphp
@if(empty($logo))
    <div>
        <h4><a href="{{ site_url() }}">{{get_option('website_title', 'website')}}</a></h4>
    </div>
@else
    <div class="checkout-v2-logo">
        <a href="{{ site_url() }}">
         <img src="{{ $logo }}"/>
        </a>
    </div>
@endif

@yield('logo-right-link')
@hasSection('logo-right-link')
@endif
