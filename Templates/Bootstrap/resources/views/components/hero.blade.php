
<div class="row align-items-center">

    @if(isset($image))
        <div class="col-12 col-md-5 col-lg-6 order-md-2">
            <img src="{{ $image }}" class="img-fluid mw-md-150 mw-lg-130 mb-6 mb-md-0">
        </div>
    @endif

    <div class="col-12 col-md-7 col-lg-6 order-md-1">

        @if(isset($title))
            <div>  {{ $title }} </div>
        @endif

        @if(isset($content))
            <div> {{ $content }}  </div>
        @endif

        @if(isset($actions))
            <div> {{ $actions }}  </div>
        @endif

    </div>
</div>
