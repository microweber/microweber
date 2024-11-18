<div class="mw-layout-background-block" style="background-color: {{ $background_color }};">

    @if($background_image)
        <div class="mw-layout-background-node" style="background-image: url('{{ $background_image }}');">
        </div>
    @endif

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
        height: 100vh; /* Adjust height as needed */
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
        z-index: -1; /* Send video behind other content */
    }
    .mw-layout-background-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Adjust overlay color and opacity */
        z-index: 1; /* Bring overlay above video */
    }
</style>
