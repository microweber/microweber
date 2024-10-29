@php
/*

type: layout

name: Default

description: Default Facebook Page View

*/
@endphp

<style>
    .fb-page,
    .fb-page iframe[style] {
        max-width: 100% !important;
    }
</style>

<div class="row">
    <div class="col-xs-12 fb-page">
        <iframe src="https://www.facebook.com/plugins/page.php?href={{ $fbPage }}{{ $timeline }}&width={{ $width }}&height={{ $height }}&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile={{ $friends }}"
                width="{{ $width }}" height="{{ $height }}" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
    </div>
</div>

@if (is_admin())
    {!! notif(_e('Click here to edit the FB Page URL', true)) !!}
@endif
