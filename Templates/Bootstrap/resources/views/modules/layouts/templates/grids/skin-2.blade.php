<?php
/*
type: layout
name: Grids 2 - Icon Feature Grid
position: 2
categories: Grids
*/
?>
@php
    $gridItems = [
        ['icon' => 'mw-micon-Speed-Fast',      'title' => 'Fast by design',     'text' => 'Optimized delivery and smart caching keep every page loading in the blink of an eye.'],
        ['icon' => 'mw-micon-Shield-Protected', 'title' => 'Always protected',   'text' => 'Free SSL, daily backups and continuous monitoring guard your site around the clock.'],
        ['icon' => 'mw-micon-Star',             'title' => 'Loved by teams',     'text' => 'An intuitive editor your whole team can use — no code and no training required.'],
        ['icon' => 'mw-micon-Globe-Earth',       'title' => 'Global reach',       'text' => 'Serve visitors from edge locations worldwide so your content is always close by.'],
        ['icon' => 'mw-micon-Database-SQL',      'title' => 'Reliable storage',   'text' => 'Redundant infrastructure keeps your data safe, available and ready to grow.'],
        ['icon' => 'mw-micon-ArrowUp-Growth',    'title' => 'Scale on demand',    'text' => 'Handle traffic spikes with a single click — no downtime and no migrations.'],
    ];
@endphp
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section grids-skin-2"
    field-name="layout-grids-skin-2"
>
    <x-row class="text-center safe-mode">
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <div class="regular-mode">
                <x-section-heading tag="h2" subtitle="A complete toolkit that grows with you." class="fw-bold">Everything in one grid</x-section-heading>
            </div>
        </x-col>
    </x-row>

    <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
        @foreach($gridItems as $item)
            <div class="col cloneable element text-center safe-mode">
                <div class="p-3">
                    <i class="safe-element no-typing {{ $item['icon'] }} d-block fs-1 text-primary mb-3"></i>
                    <h5 class="fw-semibold" data-mwplaceholder="Enter title here">{{ $item['title'] }}</h5>
                    <p class="text-muted regular-mode" data-mwplaceholder="Enter text here">{{ $item['text'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</x-layout-section>
