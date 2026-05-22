<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">

    <channel>
        <title>{{ $siteTitle }}</title>
        <link>{{ $siteUrl }}</link>
        <description>{{ $siteDescription }}</description>
        @foreach ($rssData as $item)
            <item>
                <title>{{ $item['title'] }}</title>
                <link>{{ $item['url'] }}</link>
                {{-- task-2026-05-22-d8532e / AI-844 — strip HTML tags and limit to 280
                     chars. Pre-fix: description was literally the item URL, which is
                     useless for RSS consumers. The controller sets $item['description']
                     to the processed HTML content body; strip_tags removes markup so
                     <description> carries a readable plain-text excerpt. --}}
                <description>{{ \Illuminate\Support\Str::limit(strip_tags($item['description'] ?? ''), 280) }}</description>

                @if(!empty($item['image_url']) and is_string($item['image_url']))
                    <enclosure url="{{ $item['image_url'] }}" type="{{ $item['image_type'] }}" />
                @endif

                @php
                    if (!empty($item['categories'])) {
                        foreach ($item['categories'] as $catItem) {
                            echo '<category label="'.htmlentities($catItem['title']).'"  term="'.htmlentities($catItem['url']).'"></category>';
                         }
                    }
               @endphp

                @php
                    if (!empty($item['tags'])) {
                        foreach ($item['tags'] as $tag) {
                            echo '<tag term="'.$tag.'"></tag>';
                        }
                    }
               @endphp

                @if(!empty($item['description']))
                <content type="html" xml:base="{{ $item['url'] }}"><![CDATA[ {{ $item['description'] }} ]]></content>
                @endif

            </item>
        @endforeach
    </channel>

</rss>
