<div class="mw-layout-background-block"
     @if($background_color)
     style="background-color: {{ $background_color }};"
    @endif
>


        <div class="mw-layout-background-node"
             @if($background_image)
             style="background-image: url('{{ $background_image }}');"
            @endif
        >
        </div>


    @if($background_video)
        <video src="{{ $background_video }}" autoplay muted loop playsinline class="mw-layout-background-video"></video>
    @endif

    <div class="mw-layout-background-overlay">
     </div>
</div>

<style>
    .mw-layout-background-block {
        position: relative;
        width: 100%;
        height: 100vh;
        background-size: cover;
        background-position: center;
    }
    .mw-layout-background-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }
    .mw-layout-background-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }
</style>
