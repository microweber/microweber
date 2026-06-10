<div {{ $attributes->merge(['class' => 'stat-counter text-center']) }}>
    <div class="stat-counter-value display-4 fw-bold">
        @if($prefix)<span class="stat-prefix">{{ $prefix }}</span>@endif
        <span data-mwplaceholder="Enter number">{{ $value }}</span>
        @if($suffix)<span class="stat-suffix">{{ $suffix }}</span>@endif
    </div>
    @if($label)
        <p class="stat-counter-label text-muted mt-2" data-mwplaceholder="Enter label">{{ $label }}</p>
    @endif
    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
</div>