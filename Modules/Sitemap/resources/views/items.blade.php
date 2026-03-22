<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach($itemsData as $itemData)
    <url>
        <loc>{{ $itemData['original_link'] }}</loc>
        <lastmod>{{ $itemData['updated_at'] }}</lastmod>
        @if(isset($itemData['changefreq']))
        <changefreq>{{ $itemData['changefreq'] }}</changefreq>
        @endif
        @if(isset($itemData['priority']))
        <priority>{{ $itemData['priority'] }}</priority>
        @endif
        @if(!empty($itemData['multilanguage_links'] ))
            @foreach($itemData['multilanguage_links'] as $locale => $linkData)
            <xhtml:link
                rel="alternate"
                hreflang="{{ $linkData['meta_locale'] }}"
                href="{{ $linkData['link'] }}"/>
            @endforeach
        @endif
    </url>
    @endforeach
</urlset>

