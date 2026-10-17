<div class="mw-licenses-modal">
    <div>
        {{ $this->table }}
    </div>

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
