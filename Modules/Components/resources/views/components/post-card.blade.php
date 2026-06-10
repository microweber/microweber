<div {{ $attributes->merge(['class' => 'card h-100 overflow-hidden ' . $class]) }}>
    @if($image)
        @if($link)
            <a href="{{ $link }}">
                <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}" style="object-fit: cover; height: 220px;">
            </a>
        @else
            <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}" style="object-fit: cover; height: 220px;">
        @endif
    @endif
    <div class="card-body d-flex flex-column">
        @if($date)
            <small class="text-muted mb-2 d-block">{{ $date }}</small>
        @endif
        @if($title)
            @if($link)
                <a href="{{ $link }}" class="text-decoration-none text-dark">
                    <h5 class="card-title">{{ $title }}</h5>
                </a>
            @else
                <h5 class="card-title">{{ $title }}</h5>
            @endif
        @endif
        @if($author)
            <small class="text-muted mb-2">{{ __('By') }} {{ $author }}</small>
        @endif
        @if($description)
            <p class="card-text">{{ $description }}</p>
        @endif
        @if(isset($body))
            {{ $body }}
        @endif
        @if($link)
            <div class="mt-auto">
                <a href="{{ $link }}" class="btn btn-outline-primary btn-sm">{{ $readMoreText }}</a>
            </div>
        @endif
        {{ $slot }}
    </div>
</div>