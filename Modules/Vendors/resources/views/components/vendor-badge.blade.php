@if ($vendor)
    <div class="mw-vendor-badge my-2">
        <span class="mw-vendor-badge__label text-muted">{{ _e('Sold by', true) }}</span>
        <a href="{{ route('vendors.show', $vendor->slug) }}"
           class="mw-vendor-badge__link fw-semibold text-decoration-none">
            {{ $vendor->display_name }}
        </a>
    </div>
@endif
