<div {{ $attributes->merge(['class' => 'card']) }}>
    @if(isset($image))
        <img src="{{ $image }}" class="card-img-top" alt="Card image">
    @endif

    @if(isset($header))
        <div class="card-header {{ $headerClass }}">
            {{ $header }}
        </div>
    @endif

    <div class="card-body {{ $bodyClass }}">
        @if(isset($content))
            {{ $content }}
         @endif
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="card-footer {{ $footerClass }}">
            {{ $footer }}
        </div>
    @endif
</div>
