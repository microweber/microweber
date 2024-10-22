<div

    @if($align == 'center')
        class="text-center"
    @endif

    @if($align == 'right')
        class="text-right"
    @endif

>

    @if (isset($title))
        <div class="edit">
            {{ $title }}
        </div>
    @endif

    @if (isset($content))
        <div class="edit">
            {{ $content }}
        </div>
    @endif

</div>
