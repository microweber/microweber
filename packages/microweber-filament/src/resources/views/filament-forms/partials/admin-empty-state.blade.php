

@php
    $mwAdminEmptyHeading      = $heading ?? '';
    $mwAdminEmptyDescription  = $description ?? null;
    $mwAdminEmptyCtaLabel     = $cta_label ?? null;
    $mwAdminEmptyCtaHref      = $cta_href ?? null;
    $mwAdminEmptyCtaAria      = $cta_aria ?? $mwAdminEmptyCtaLabel;
    $mwAdminEmptyIcon         = $icon ?? null;
    $mwAdminEmptyExtraClass   = $extra_class ?? '';
@endphp

<div class="mw-admin-empty-state {{ e($mwAdminEmptyExtraClass) }}" data-mw-admin-empty-state="true">
    @if ($mwAdminEmptyIcon)
        <span class="mw-admin-empty-state__icon" aria-hidden="true">
            {{-- Heroicon name passed through to Filament's icon helper if present.
                 Falls back to nothing if neither blade-ui-kit nor @svg helper is wired. --}}
            @if (function_exists('svg'))
                {!! svg($mwAdminEmptyIcon, 'h-12 w-12 text-gray-400') !!}
            @endif
        </span>
    @endif

    <h2 class="mw-admin-empty-state__heading">
        {{ $mwAdminEmptyHeading }}
    </h2>

    @if ($mwAdminEmptyDescription)
        <p class="mw-admin-empty-state__body">
            {{ $mwAdminEmptyDescription }}
        </p>
    @endif

    @if ($mwAdminEmptyCtaLabel && $mwAdminEmptyCtaHref)
        <div class="mw-table-empty-cta-wrap mw-admin-empty-state__cta-wrap">
            <a
                href="{{ $mwAdminEmptyCtaHref }}"
                class="mw-table-empty-cta mw-admin-empty-state__cta"
                aria-label="{{ $mwAdminEmptyCtaAria ?? $mwAdminEmptyCtaLabel }}"
            >
                {{ $mwAdminEmptyCtaLabel }}
            </a>
        </div>
    @endif
</div>
