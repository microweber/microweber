<div {{ $attributes->merge(['class' => 'progress']) }}>
    <div 
        class="progress-bar{{ $type ? ' bg-' . $type : '' }}{{ $striped ? ' progress-bar-striped' : '' }}{{ $animated ? ' progress-bar-animated' : '' }}"
        role="progressbar"
        style="width: {{ $value }}%"
        aria-valuenow="{{ $value }}"
        aria-valuemin="0"
        aria-valuemax="100"
    >
        {{ $slot }}
    </div>
</div>
