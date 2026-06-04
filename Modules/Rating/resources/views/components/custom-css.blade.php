@php
    if (isset($starSize) && $starSize) {
        $starSize = intval($starSize) . 'px!important;';
    }
    if (isset($starColor) && $starColor) {
        $starColor = $starColor . '!important;';
    }
    if (isset($starBgColor) && $starBgColor) {
        $starBgColor = $starBgColor . '!important;';
    }
@endphp

<style>
    #{{ $params['id'] ?? 'rating-stars' }} .starrr span {
        font-size: {{ $starSize ?? '24px' }};
        color: {{ $starColor ?? '#FFD700' }};
        background: {{ $starBgColor ?? 'transparent' }};
        border-radius: 50%;
        padding: 2px;
        display: inline-block;
    }

    /* AI-301 / task-2026-06-04-ratetap — each interactive rating star is
       only ~28px (font-size + 2px padding), below the WCAG 2.5.5 44px
       touch floor. On mobile / coarse pointers expand the tap target to
       44x44 without enlarging the star glyph (keeps the configured
       starSize). 5 stars x 44px = 220px, fits the 390px viewport. */
    @media (max-width: 768px), (pointer: coarse) {
        #{{ $params['id'] ?? 'rating-stars' }} .starrr span {
            min-width: 44px;
            min-height: 44px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    }
</style>

