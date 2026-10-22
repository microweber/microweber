<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?php echo '<?xml-stylesheet type="text/xsl" href="' . site_url('sitemap.xsl') . '"?>' . "\n"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:mw="http://microweber.com/schemas/sitemap-ext/1.0">
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
        {{-- OFFICIAL image sitemap extension — indexed by crawlers. --}}
        @if(!empty($itemData['images']))
            @foreach($itemData['images'] as $imageUrl)
        <image:image>
            <image:loc>{{ $imageUrl }}</image:loc>
        </image:image>
            @endforeach
        @endif
        {{-- Custom mw: namespace — ignored by crawlers (keeps the sitemap
             protocol-valid), rendered by our sitemap.xsl for human readers. --}}
        @if(isset($itemData['price']))
        <mw:price>{{ $itemData['price'] }}</mw:price>
        @endif
        @if(!empty($itemData['categories']))
            @foreach($itemData['categories'] as $categoryName)
        <mw:category>{{ $categoryName }}</mw:category>
            @endforeach
        @endif
        @if(!empty($itemData['tags']))
            @foreach($itemData['tags'] as $tagName)
        <mw:tag>{{ $tagName }}</mw:tag>
            @endforeach
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

