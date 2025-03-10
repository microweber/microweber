{{--
type: layout
name: Tag Cloud
description: Tag cloud template with varying sizes based on usage
--}}

@if(!empty($content_tags))
    <div class="tag-cloud">
        @foreach($content_tags as $tag)
            @php
                // Get the count of content items with this tag
                $count = function_exists('content_tags_count') ? content_tags_count($tag) : 1;
                
                // Calculate a size factor based on count (1-5)
                $sizeFactor = min(5, max(1, ceil($count / 2)));
                
                // Map size factor to font size
                $fontSize = [
                    1 => '0.8em',
                    2 => '1em',
                    3 => '1.2em',
                    4 => '1.4em',
                    5 => '1.6em'
                ][$sizeFactor];
                
                // Map size factor to opacity
                $opacity = 0.5 + ($sizeFactor * 0.1);
            @endphp
            
            <a href="{{ $tags_url_base }}?tags={{ $tag }}" class="cloud-tag" 
               style="font-size: {{ $fontSize }}; opacity: {{ $opacity }};">
                {{ $tag }}
                @if(isset($options['show_tag_counts']) && $options['show_tag_counts'])
                    <span class="tag-count">({{ $count }})</span>
                @endif
            </a>
        @endforeach
    </div>

    <style>
        .tag-cloud {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        
        .cloud-tag {
            display: inline-block;
            margin: 5px;
            padding: 5px 10px;
            background-color: {{ $options['tag_color'] ?? '#e0f0ff' }};
            color: #333;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .cloud-tag:hover {
            background-color: {{ $options['tag_hover_color'] ?? '#c0e0ff' }};
            transform: scale(1.1);
            z-index: 1;
        }
        
        .tag-count {
            font-size: 0.8em;
            opacity: 0.7;
        }
    </style>
@endif
