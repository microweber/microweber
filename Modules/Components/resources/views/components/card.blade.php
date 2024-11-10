<div {{ $attributes->merge(['class' => 'card ' . ($theme == 'dark' ? 'bg-dark text-white ' : '') . ($theme == 'danger' ? 'bg-danger text-white ' : '') . ($theme == 'success' ? 'bg-success text-white ' : '') . $class ?? '']) }}>
    @if(isset($image) and $image)
        <img src="{{ $image }}" class="card-img-top" />
    @endif

    <div class="card-body">
        @if(isset($title) and $title)
            <h5 class="card-title safe-mode">
                {{ $title }}
            </h5>
        @endif
        @if(isset($content) and $content)
            <div class="safe-mode">
                {{ $content }}
            </div>
        @endif
    </div>
    @if(isset($footer) and $footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
