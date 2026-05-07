{{-- audit-test 2026-05-07 PM TICKET-AV bundle: migrated `<div bg-image>`
     to real `<img>` (closes CSS-injection vector + adds alt for SR users in
     the Filament admin content list). --}}
<a href="#">
    @if($content->media()->first())
        <img src="{{ $content->thumbnail(640, 480, true) }}"
             alt="{{ $content->title ?? __('Content thumbnail') }}"
             loading="lazy"
             decoding="async"
             class="h-12 w-12 d-block"
             style="object-fit: cover; object-position: center center;">
    @else
        @include('modules.content::filament.admin.icon', ['content'=>$content])
    @endif
</a>
