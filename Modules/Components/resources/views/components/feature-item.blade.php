<div {{ $attributes->merge(['class' => $colClass . ' mb-5 cloneable element text-center safe-mode background-color-element']) }}>
    @if($icon)
        <i class="features-skin-2-icons mb-2 safe-element no-typing {{ $icon }}"></i>
    @endif

    @if($title)
        <div class="regular-mode">
            <h4 data-mwplaceholder="Enter title here">{{ $title }}</h4>
        </div>
    @endif

    @if($text)
        <div class="text-center mt-3 regular-mode">
            <p data-mwplaceholder="Enter text here">{{ $text }}</p>
        </div>
    @endif

    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
</div>