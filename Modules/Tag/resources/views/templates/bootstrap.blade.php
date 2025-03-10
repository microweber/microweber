{{--
type: layout
name: Bootstrap
description: Bootstrap template for tags
--}}

@if(!empty($content_tags))
    <div class="tags-container">
        @foreach($content_tags as $tag)
            <a href="{{ $tags_url_base }}?tags={{ $tag }}" class="badge bg-primary text-decoration-none me-2 mb-2">
                {{ $tag }}
                @if(isset($options['show_tag_counts']) && $options['show_tag_counts'])
                    <span class="badge bg-light text-dark">{{ content_tags_count($tag) }}</span>
                @endif
            </a>
        @endforeach
    </div>

    <style>
        .tags-container {
            display: flex;
            flex-wrap: wrap;
        }
        
        .badge {
            font-size: {{ $options['tag_size'] == 'small' ? '0.75rem' : ($options['tag_size'] == 'large' ? '1rem' : '0.875rem') }};
            padding: 0.5em 0.75em;
            transition: all 0.3s;
        }
        
        .badge.bg-primary {
            background-color: {{ $options['tag_color'] ?? '#0d6efd' }} !important;
        }
        
        .badge.bg-primary:hover {
            background-color: {{ $options['tag_hover_color'] ?? '#0a58ca' }} !important;
        }
    </style>
@endif
