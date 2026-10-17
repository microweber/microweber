
@if (is_admin())
{{-- Filament v5: <x-filament::button> drops the passed `class`, so the
     `admin-toolbar-live-edit` marker lives on this WRAPPER and the CSS targets
     `.admin-toolbar-live-edit .fi-btn` (see general-styles.css). --}}
<div class="flex admin-toolbar-live-edit">
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
