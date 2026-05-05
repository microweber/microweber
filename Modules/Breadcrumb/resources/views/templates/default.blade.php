<?php

/*

  type: layout

  name: Default

  description: Default template

*/

?>

@include('modules.breadcrumb::partials.breadcrumb_params')

@php
    /*
     * task-2026-05-05-3bd724 — drunk-designer audit (breadcrumb.md
     * QW): emit BreadcrumbList JSON-LD so search engines render the
     * site hierarchy as a rich snippet in results pages. Builds the
     * list once from the same $homepage + $data already in scope.
     */
    $breadcrumbJsonLdItems = [];
    $breadcrumbJsonLdPos = 1;
    if (!empty($homepage['url']) && !empty($homepage['title'])) {
        $breadcrumbJsonLdItems[] = [
            '@type' => 'ListItem',
            'position' => $breadcrumbJsonLdPos++,
            'name' => $homepage['title'],
            'item' => $homepage['url'],
        ];
    }
    if (!empty($data) && is_array($data)) {
        foreach ($data as $breadcrumbJsonLdItem) {
            if (empty($breadcrumbJsonLdItem['title'])) {
                continue;
            }
            $entry = [
                '@type' => 'ListItem',
                'position' => $breadcrumbJsonLdPos++,
                'name' => $breadcrumbJsonLdItem['title'],
            ];
            if (!empty($breadcrumbJsonLdItem['url'])) {
                $entry['item'] = $breadcrumbJsonLdItem['url'];
            }
            $breadcrumbJsonLdItems[] = $entry;
        }
    }
@endphp

@if(!empty($breadcrumbJsonLdItems))
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbJsonLdItems,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endif

<nav aria-label="Breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ $homepage['url'] }}">{{ $homepage['title'] }}</a>
        </li>
        @if(!empty($data))
            @foreach($data as $item)
                @if($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                    </li>
                @endif
            @endforeach
        @endif
    </ol>
</nav>
