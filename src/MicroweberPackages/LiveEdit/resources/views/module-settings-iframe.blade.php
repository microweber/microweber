<div id="settings-container-iframe">
{{-- Height is viewport-relative, NOT a fixed 1000px. In Live Edit the settings load
     inside a modal whose content area is capped to the browser viewport; a hardcoded
     1000px iframe overflowed it, and any nested action modal (e.g. "Add menu item")
     uses `height: 100vh` relative to THIS iframe — so on a fixed 1000px iframe its
     footer (Save / Cancel) landed below the visible viewport and was unreachable.
     Sizing to the viewport keeps the iframe (and the nested modal) within view so the
     save button stays visible; taller module content scrolls inside the iframe. --}}
<iframe src="{{ $iframeUrl }}"
        title="Module settings"
        aria-label="Module settings panel"
        frameborder="0" style="width: 100%; height: calc(100vh - 10rem); min-height: 400px; max-height: 1000px;"
        data-auto-height="false"
></iframe>
</div>



