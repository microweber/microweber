<div class="mw-layout-background-block">
        <div class="mw-layout-background-node"
             @if($background_image)
             style="background-image: url('{{ $background_image }}');"
            @endif
        >
        </div>
        @if($background_video)
            <video src="{{ $background_video }}" autoplay muted loop playsinline class="mw-layout-background-video"></video>
        @endif

        <div class="mw-layout-background-overlay"
             @if($background_color)
                 style="background-color: {{ $background_color }};"
            @endif
        >
         </div>
</div>
