<div {{ $attributes->merge(['class' => 'card h-100 overflow-hidden ' . $class]) }}>
    @if($image)
        @if($link)
            <a href="{{ $link }}">
                <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}" style="object-fit: cover; height: 200px;">
            </a>
        @else
            <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}" style="object-fit: cover; height: 200px;">
        @endif
    @endif
    <div class="card-body d-flex flex-column">
        @if($date)
            <small class="text-muted mb-2">{{ $date }}</small>
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
        @if($description)
            <p class="card-text">{{ $description }}</p>
        @endif
        @if(isset($body))
            {{ $body }}
        @endif
        {{ $slot }}
    </div>
    @if(isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>