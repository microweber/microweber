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
                <description>{{ $item['url'] }}</description>
                @if(!empty($item['image_url']))
                    <enclosure url="{{ $item['image_url'] }}" length="{{ $item['image_size'] }}" type="{{ $item['image_type'] }}" />
                @endif
            </item>
        @endforeach
    </channel>

</rss>