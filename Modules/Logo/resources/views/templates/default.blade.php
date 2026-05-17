<div class="logo-module">
    {{-- task-2026-05-17-e2e29a / AI-805 -- collapsed dead conditional.
         Pre-fix had a Blade if/else where BOTH arms emitted identical
         href={{ site_url() }} (both arms returned the same value, so
         the condition was dead code). Designer dispatch offered Path A
         (collapse, no behaviour change -- shipped at task-e2e29a) vs
         Path B (formalise live-edit-disabled-navigation -- the AI-805a
         follow-up).

         task-2026-05-17-af2b73 / AI-805a -- Path B shipped per
         designer's AI-805 ACK authorization. Recommended shape:
         is_live_edit() ? 'javascript:void(0)' : site_url(). Tooltip
         "Use the menu to navigate" + aria-disabled="true" added so AT
         users understand why the link doesn't navigate.

         Product-call validation: designer's own audit work hit the
         data-loss bug "happened to me twice today" -- clicking the
         logo mid-edit navigated away from the live-edit session and
         lost unsaved work. Path B prevents the lossage at the entry
         point. javascript:void(0) (not '#') chosen because '#' jumps
         to top of page if not handled; void(0) is explicit "do
         nothing" semantics.

         Public mode unchanged: href={{ site_url() }} as it always
         was. The single-href contract preserved AI-805 (no dead
         conditional, no Blade @if/@else/@endif overhead) -- the only
         conditional now is the runtime is_live_edit() ternary which
         is a behaviour gate, not a dead branch. --}}
    <a href="{{ is_live_edit() ? 'javascript:void(0)' : site_url() }}" class="logo-link"@if(is_live_edit()) title="Use the menu to navigate" aria-disabled="true"@endif>
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
    /*
     * task-2026-05-17-5b0a92 / AI-803 CHANGE — runtime
     * parent-flex-collapse fix. The original AI-803 ship
     * (task-fa5dc3) correctly gated `.logo-module { min-width:0;
     * overflow:hidden }` inside `@media (max-width: 575px)` so the
     * parent column no longer collapsed unconditionally. But
     * designer's tier-3 desktop probe found the img STILL rendered
     * at 0×0 because the parent template wrapper carries the
     * Bootstrap class combo `col-xl-4 w-auto`: `col-xl-4` sets
     * 33.33% width, but `w-auto !important` overrides to "fit
     * content" and wins via Bootstrap utility source-order
     * specificity. Parent width = content width; content =
     * `.logo-link > img`; without `display: inline-block` on
     * `.logo-link`, the anchor defaults to `display: inline`,
     * collapses to 0, and the img's `max-width: 100%` (of a
     * collapsed parent) also collapses to 0×0.
     *
     * Designer's Option A fix: give `.logo-link` `display:
     * inline-block` at desktop too (mirrors the same declaration
     * already in the ≤575px block from AI-803 task-fa5dc3). The
     * anchor wraps to its content width (the img's natural
     * dimensions), parent fits content, img renders at natural
     * 300×82.
     *
     * Stage-2 cascade-loss family (sibling to AI-697 v3, AI-786
     * v2): source change correct but a sibling rule (or parent
     * flex context) needed runtime adjustment. Pattern signature:
     * "source-pin pass + consumer-runtime silent."
     */
    .logo-module .logo-link {
        display: inline-block;
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
