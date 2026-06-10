<div {{ $attributes->merge(['class' => 'card border-0 text-center ' . $class]) }}>
    @if($image)
        <div class="mx-auto mt-3" style="width: 150px; height: 150px; overflow: hidden;">
            <img src="{{ $image }}" class="rounded-circle img-fluid" alt="{{ $name }}" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    @endif
    <div class="card-body">
        @if($name)
            <h5 class="mb-1">{{ $name }}</h5>
        @endif
        @if($role)
            <p class="text-muted mb-2">{{ $role }}</p>
        @endif
        @if($bio)
            <p class="mb-2">{{ $bio }}</p>
        @endif
        @if($website)
            <a href="{{ $website }}" target="_blank" rel="noopener noreferrer" class="d-block mb-2">{{ $website }}</a>
        @endif
        @if(isset($socials))
            <div class="mt-2">{{ $socials }}</div>
        @endif
    </div>
    {{ $slot }}
</div>