<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<data>
    @foreach ($rssData as $item)
        <post>
            <id>{{ $item['id'] }}</id>
            <Title><![CDATA[{{ $item['title'] }}]]></Title>
            <Content><![CDATA[{{ $item['description'] }}]]></Content>

            <Link>{{ $item['url'] }}</Link>

            <Categories><![CDATA[
                @php
                    $categories = [];
                    if (!empty($item['categories'])) {
                        foreach ($item['categories'] as $catItem) {
                            $categories[] = $catItem['title'];
                        }
                    }
                    $categories = implode('|', $categories);
                @endphp
                {{$categories}}
                ]]></Categories>

            <Tags><![CDATA[
            @php
                $tags = [];
                if (!empty($item['tags'])) {
                    $tags = $item['tags'];
                }
                $tags = implode('|', $tags);
            @endphp
            {{$tags}}
            ]]></Tags>

            @if(!empty($item['image_url']))
                <ImageURL><![CDATA[{{$item['image_url']}}]]></ImageURL>
            @endif

        </post>
    @endforeach
</data>
