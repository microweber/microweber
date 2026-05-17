<div class="logo-module">
    <a
        @if(is_live_edit())
            href="{{ site_url() }}"
        @else

            href="{{ site_url() }}"
        @endif


        class="logo-link">
        @if(isset($logoimage) && !empty($logoimage))
            {{-- task-2026-05-17-488fa3 / AI-804 -- WCAG 2.1 SC 1.1.1 fix.
                 Pre-fix alt="{{ isset($text) ? $text : '' }}" rendered
                 empty alt when no module text override was set --
                 screen-reader users heard nothing on the brand mark.
                 Fallback precedence: user-set $text -> configured site
                 website_title -> literal 'Home' safety net (never empty).
                 Designer dispatch suggested option_get(); Microweber's
                 canonical helper is get_option(key, group) -- matches
                 the existing pattern at src/MicroweberPackages/App
                 /resources/views/email/simple.blade.php:348. --}}
            <img src="{{ $logoimage }}" alt="{{ isset($text) && $text !== '' ? $text : (get_option('website_title', 'website') ?: 'Home') }}" style="max-width: {{ isset($size) ? $size . 'px' : '200px' }};"/>
        @elseif(isset($text) && !empty($text))
            <span class="logo-text" style="color: {{ isset($text_color) ? $text_color : 'inherit' }}; font-family: {{ isset($font_family) ? $font_family : 'inherit' }}; font-size: {{ isset($font_size) ? $font_size : '30' }}px;">
                {{ $text }}
            </span>
        @else
            <span class="logo-text">
               {!! lnotif('Click to add logo') !!}
            </span>
        @endif
    </a>
</div>

<style>
    /*
     * task-2026-05-17-fa5dc3 / AI-803 [P1 HIGHEST] — logo invisible on
     * frontend. Pre-fix `.logo-module { min-width: 0; overflow: hidden }`
     * was declared UNCONDITIONALLY despite a docblock saying it was
     * meant for the narrow-viewport (≤390px) hamburger-row scenario.
     * Effect: at every viewport (including desktop) the parent flex
     * column collapsed to 0×0, so the SVG logo (300×82 natural) loaded
     * but rendered invisible. Brand collapse on every install.
     *
     * Stage-3 wrong-surface-mount: fix designed for one viewport
     * mounted on all viewports.
     *
     * Fix: gate the narrow-viewport rules inside `@media (max-width: 575px)`
     * (Bootstrap 5 `sm` breakpoint — first breakpoint above the 390px
     * iPhone class). At ≥576px the parent stays at its natural width
     * and the logo renders visible.
     *
     * `.logo-module img { max-width: 100%; height: auto }` stays
     * OUTSIDE the media query — that's responsive-image safety,
     * applies at every viewport.
     *
     * Sibling tickets per designer dispatch (same template, NOT
     * shipped in this slice — separate dispatches AI-804 + AI-805):
     *   - AI-804 (Medium): logo alt="" empty fallback - WCAG 1.1.1
     *   - AI-805 (Low):    dead is_live_edit() blade branch (both
     *                      arms emit identical href)
     */
    .logo-module {
        /*text-align: center;*/
        margin: 20px 0;
    }
    .logo-module img {
        max-width: 100%;
        height: auto;
    }
    .logo-text {
        display: inline-block;
        margin-top: 10px;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    @media (max-width: 575px) {
        /*
         * Narrow viewport: the logo shares its header row with the
         * hamburger icon. Without `min-width: 0` the logo's intrinsic
         * text width forces the column to stay at full size and pushes
         * the hamburger off-screen. min-width:0 + overflow:hidden lets
         * the column shrink; the inner ellipsis on .logo-link / .logo
         * -text gracefully truncates very long brand names instead of
         * breaking the layout. Originally landed as cross-viewport
         * rule (AI-803 root cause); scoped to ≤575px here.
         */
        .logo-module {
            min-width: 0;
            overflow: hidden;
        }
        .logo-module .logo-link {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }
    }
</style>
