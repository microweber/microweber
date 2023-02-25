@include('admin::layouts.partials.header')


@php
    event_trigger('mw.admin.header');
@endphp


{!! $content !!}


@include('admin::layouts.partials.footer')

