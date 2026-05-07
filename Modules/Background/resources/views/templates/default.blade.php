{{-- audit-test 2026-05-07 PM TICKET-AV bundle (Option A — safe_css_url helper):
     this is the page-level background-image module, not a content image. Migrating
     to a real <img> would change the architecture (the bg-node intentionally sits
     behind the page content and pairs with a video child + an overlay sibling).
     safe_css_url() closes the CSS-injection vector while preserving the layout. --}}
<div class="mw-layout-background-block">
    <div class="mw-layout-background-node"
         @if($background_image)
         style="background-image: url('{{ safe_css_url($background_image) }}');"
        @endif
    >

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
