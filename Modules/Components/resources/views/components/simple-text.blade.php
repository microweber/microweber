<div {{ $attributes->merge(['class' => ($align == 'center' ? 'text-center ' : '') . ($align == 'right' ? 'text-right ' : '') . $class]) }}>
    @if (isset($title))
        <div class="safe-mode">
            {{ $title }}
        </div>
    @endif

    @if (isset($content))
        <div class="safe-mode">
            {{ $content }}
        </div>
    @endif
</div>
