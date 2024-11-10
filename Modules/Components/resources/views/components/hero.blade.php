<div {{ $attributes->merge(['class' => 'row align-items-center ' . $class]) }}>
    @if(isset($image) and $image)
        <div class="safe-mode col-12 col-md-5 col-lg-6 order-md-2">
            <img src="{{ $image }}" class="img-fluid mw-md-150 mw-lg-130 mb-6 mb-md-0">
        </div>
    @endif

    <div class="col-12 col-md-7 col-lg-6 order-md-1">
        @if(isset($title) and $title)
            <div class="safe-mode">  {{ $title }} </div>
        @endif

        @if(isset($content) and $content)
            <div class="safe-mode"> {{ $content }}  </div>
        @endif

        @if(isset($actions) and $actions)
            <div class="safe-mode"> {{ $actions }}  </div>
        @endif
    </div>
</div>
