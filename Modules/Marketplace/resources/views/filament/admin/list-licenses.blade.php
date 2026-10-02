<div class="mw-licenses-modal">
    <div>
        {{ $this->table }}
    </div>

    {{-- task-2026-10-02-licensehdr — the licenses table renders a nested gray
         header band + bordered card INSIDE a modal that already has its own
         "Licenses" title, so the header read as a boxy card-in-a-card with a
         heavy divider. Flatten it: drop the inner card chrome and the gray tint
         so the heading/description + Refresh/Add actions sit as one clean header
         row on white, with a single subtle divider before the list. Wrapped in
         @assets (injected once into head, deduped) because a bare <style> as a
         second root would break Livewire's single-root rule; unlayered so it
         beats Filament's layered table utilities. --}}
    @assets
    <style>
        /* body.fi-panel-admin prefix so these out-specify the theme's own
           body.fi-panel-admin .fi-ta-ctn / .fi-ta-header !important rules
           (a bare .mw-licenses-modal selector ties on !important but loses on
           specificity). */
        body.fi-panel-admin .mw-licenses-modal .fi-ta,
        body.fi-panel-admin .mw-licenses-modal .fi-ta-ctn {
            border: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            background: transparent !important;
        }
        body.fi-panel-admin .mw-licenses-modal .fi-ta-header {
            background: transparent !important;
            border-bottom: 1px solid rgb(232, 238, 244) !important;
            padding: 0 0 16px !important;
            margin-bottom: 8px !important;
        }
        body.fi-panel-admin .mw-licenses-modal .fi-ta-header-heading {
            font-size: 1.05rem !important;
        }
        /* Empty state: it owns the whole modal body now, so give it room to
           breathe rather than floating against the divider. */
        body.fi-panel-admin .mw-licenses-modal .fi-ta-empty-state {
            padding-top: 32px !important;
            padding-bottom: 24px !important;
        }
    </style>
    @endassets
</div>
