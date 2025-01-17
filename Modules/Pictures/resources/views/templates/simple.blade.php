{{--
type: layout
name: Simple
description: Simple Pictures List Template
--}}

@if(isset($data) && is_array($data) && $data)
    @php
        $rand = uniqid();
    @endphp

    <script>mw.moduleCSS("{{ asset('modules/pictures/css/style.css') }}");</script>
    
    <div class="mw-module-images{{ isset($no_img) && $no_img ? ' no-image' : '' }}">
        <div class="mw-pictures-list mw-images-template-default-grid" id="mw-gallery-{{ $rand }}">
            @php $count = -1; @endphp
            @foreach($data as $item)
                @php $count++; @endphp
                <div class="mw-pictures-item mw-pictures-item-{{ $item['id'] ?? '' }}">
                    <div class="thumbnail"
                         onclick="mw.gallery(gallery{{ $rand }}, {{ $count }})">
                        <span class="pic-valign">
                            <span class="pic-valign-cell">
                                <img src="{{ thumbnail($item['filename'] ?? '', 300) }}" 
                                     alt="{{ isset($item['title']) ? addslashes($item['title']) : '' }}"/>
                            </span>
                        </span>
                    </div>
                </div>
            @endforeach
            
            <script>
                gallery{{ $rand }} = [
                    @foreach($data as $item)
                        {
                            image: "{{ isset($item['filename']) ? $item['filename'] : '' }}",
                            description: "{{ isset($item['title']) ? $item['title'] : '' }}"
                        },
                    @endforeach
                ];
            </script>
        </div>
    </div>
@else
    @include('modules.pictures::partials.no-pictures')
@endif
