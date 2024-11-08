<div

    @if($align == 'center')
        class="text-center"
    @endif

    @if($align == 'right')
        class="text-right"
    @endif

>

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
