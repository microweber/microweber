<div class="w-full">

    @php
    if (!isset($maxHeight)) {
        $maxHeight = '36rem';
    }
    @endphp

    <div class="image-column-cropped w-full" style="height:{{$maxHeight}}; background-image:url('{!! $image !!}');background-size: cover;background-position: top;">

    </div>

</div>
