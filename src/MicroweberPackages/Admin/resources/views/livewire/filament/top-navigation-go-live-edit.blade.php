{{-- AI-86 / TICKET-Q (cycle-97 2026-05-09): admin-context guard.
     This view is rendered through Filament's
     `PanelsRenderHook::GLOBAL_SEARCH_AFTER` which only fires inside
     the admin panel — but defense-in-depth: explicit `is_admin()`
     gate so a future render-hook wiring change can't accidentally
     leak the chip into a public-facing Filament panel (storefront
     account-area, customer dashboards, etc.). --}}
@if (is_admin())
<div class="flex">
<x-filament::button
    href="{{site_url('?editmode=y')}}"
    tag="a"
    icon="heroicon-m-eye"
    class="admin-toolbar-buttons admin-toolbar-live-edit"
>
    Live Edit
</x-filament::button>
</div>
@endif
