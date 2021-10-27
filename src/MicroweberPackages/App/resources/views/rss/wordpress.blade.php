<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<data>
    @foreach ($rssData as $item)
        <post>
            <id>{{ $item['id'] }}</id>
            <Title><![CDATA[{{ $item['title'] }}]]></Title>
            <Content><![CDATA[ {{ $item['description'] }} ]]></Content>

            <Link>{{ $item['url'] }}</Link>

            @if(!empty($item['image_url']))
                <ImageURL><![CDATA[{{ $item['image_url'] }}]]></ImageURL>
            @endif

        </post>
    @endforeach
</data>
