<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0"
    xmlns:g="http://base.google.com/ns/1.0">
    <channel>
        <title>{{ $siteTitle }}</title>
        <link>{{ $siteUrl }}</link>
        <description>{{ $siteDescription }}</description>
        @foreach ($rssData as $item)
            <item>
                <g:title>{{ $item['title'] }}</g:title>
                <g:description>{{ $item['description'] }}</g:description>
                @if(!empty($item['image']))
                    <g:image_link>{{ $item['image'] }}</g:image_link>
                @endif
                @if(isset($item['price']))
                    <g:price>{{ $item['price'] }}</g:price>
                @endif
            </item>
        @endforeach
    </channel>
</rss>