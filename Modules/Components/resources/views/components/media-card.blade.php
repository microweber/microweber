<div {{ $attributes->merge(['class' => 'card h-100 overflow-hidden ' . $class]) }}>
    @if($image)
        <div class="position-relative">
            @if($link)
                <a href="{{ $link }}">
                    <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}" style="object-fit: cover; height: 220px;">
                    @if($mediaType === 'video')
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <span class="bg-dark bg-opacity-50 text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="mdi mdi-play" style="font-size: 24px;"></i>
                            </span>
                        </div>
                    @endif
                </a>
            @else
                <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}" style="object-fit: cover; height: 220px;">
            @endif
        </div>
    @endif
    <div class="card-body">
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
            <p class="card-text text-muted">{{ $description }}</p>
        @endif
        @if(isset($body))
            {{ $body }}
        @endif
        {{ $slot }}
    </div>
</div>