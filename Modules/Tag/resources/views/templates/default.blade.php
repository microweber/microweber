{{--
type: layout
name: Default
description: Default template for tags
--}}

@if(!empty($content_tags))
    <div class="tags">
        @foreach($content_tags as $tag)
            <a href="{{ $tags_url_base }}?tags={{ $tag }}" class="tag">{{ $tag }}</a>
        @endforeach
    </div>

    <style>
        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .tag {
            display: inline-block;
            padding: 5px 10px;
            background-color: {{ $options['tag_color'] ?? '#f0f0f0' }};
            color: #333;
            border-radius: 4px;
            text-decoration: none;
            font-size: {{ $options['tag_size'] == 'small' ? '12px' : ($options['tag_size'] == 'large' ? '16px' : '14px') }};
            transition: background-color 0.3s;
        }
        
        .tag:hover {
            background-color: {{ $options['tag_hover_color'] ?? '#e0e0e0' }};
            color: #000;
        }
    </style>
@endif
