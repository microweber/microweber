{{-- audit-test 2026-05-08 PM TASK-017 / TICKET-AB:
     Page-level background module. Was `<div style="background-image: url(...)">`
     via safe_css_url() — TICKET-AV had deliberately left this as Option A
     because the bg-image div sits behind page content and pairs with a
     <video> child + a `.mw-layout-background-overlay` sibling.
     PM-approved Option B layout-preserving migration: convert to a real <img>
     positioned absolutely inside the same `.mw-layout-background-node` wrapper
     so the bg sits behind page content exactly as before. The <video> child
     and overlay sibling are unchanged.
     - alt="" because this is decorative page chrome, not content (WCAG 1.1.1)
     - loading="eager" because background images are above-the-fold by
       definition (no benefit to lazy here)
     - object-fit:cover preserves the prior background-size:cover visual
     Closes the CSS-injection vector that safe_css_url() was guarding against,
     and removes the last `background-image: url('{{` raw blade interpolation
     in the codebase. --}}
<div class="mw-layout-background-block">
    <div class="mw-layout-background-node position-relative" style="overflow: hidden;">

        @if($background_image)
            <img src="{{ $background_image }}"
                 alt=""
                 loading="eager"
                 decoding="async"
                 class="position-absolute top-0 start-0 w-100 h-100"
                 style="object-fit: cover; z-index: 0;"
                 aria-hidden="true">
        @endif

        @if($background_video)
            <video src="{{ $background_video }}" autoplay muted loop playsinline class="mw-layout-background-video"></video>
        @endif
    </div>


    <div class="mw-layout-background-overlay"
         @if($background_color)
             style="background-color: {{ $background_color }};"
        @endif
    >
     </div>
</div>
