<div {{ $attributes->merge(['class' => 'card border-0 text-center ' . $class]) }}>
    @if($image)
        <div class="mx-auto mt-3" style="width: 100px; height: 100px; overflow: hidden;">
            <img src="{{ $image }}" class="rounded-circle img-fluid" alt="{{ $name }}" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    @endif
    <div class="card-body">
        @if($content)
            <p class="mb-3">{{ $content }}</p>
        @endif
        @if(isset($body))
            {{ $body }}
        @endif
        @if($name)
            <h5 class="mb-0">{{ $name }}</h5>
        @endif
        @if($role)
            <small class="text-muted">{{ $role }}</small>
        @endif
        @if($company)
            <div><small class="text-muted">{{ $company }}</small></div>
        @endif
    </div>
    {{ $slot }}
</div>