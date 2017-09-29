{!! '<'.'?'.'xml version="1.0" encoding="UTF-8" ?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <atom:link href="{{site_url('rss') }} " rel="self" type="application/rss+xml" />
        <title>{{ $site_title }}</title>
        <link>{{ site_url() }} </link>
        <description>{!! $site_desc !!}</description>
        @foreach ($cont as $row)
            @if (!isset($row['description']) or $row['description'] == '')
                <?php $row['description'] = $row['content']; ?>
            @endif
            <?php $row['description'] = character_limiter(strip_tags(($row['description'])), 500); ?>
            <item>
                <title>{{$row['title']}}</title>
                <description><![CDATA[{{ $row['description'] }}]]></description>
                <link>{{content_link($row['id']) }}</link>
                <pubDate>{{date('D, d M Y H:i:s O', strtotime($row['created_at'])) }}</pubDate>
                <guid>{{content_link($row['id']) }}</guid>
            </item>
        @endforeach
    </channel>
</rss>


 