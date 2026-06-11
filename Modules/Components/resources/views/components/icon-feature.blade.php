<div {{ $attributes->merge(['class' => trim($colClass . ' cloneable element safe-mode background-color-element')]) }}>
    <div class="d-{{ $layout === 'horizontal' ? 'flex' : 'block' }} align-items-center {{ $layout === 'horizontal' ? 'justify-content-md-start justify-content-center' : 'text-center' }} safe-mode mb-2">
        @if($icon)
            <i class="me-3 safe-element no-typing {{ $icon }}" style="font-size: {{ $iconSize }};"></i>
        @endif
        @if($title)
            <div class="regular-mode">
                <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-0">{{ $title }}</h4>
            </div>
        @endif
    </div>
    @if($text)
        <div class="regular-mode">
            <p data-mwplaceholder="{{ _e('Enter text here') }}">{{ $text }}</p>
        </div>
    @endif
    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
</div>