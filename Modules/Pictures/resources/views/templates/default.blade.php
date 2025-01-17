{{--
type: layout
name: Default
description: Default Picture List
--}}

@if(isset($data) && is_array($data) && $data)
    @php
        $rand = uniqid();
    @endphp
    
    <script>mw.moduleCSS("{{ asset('modules/pictures/css/clean.css') }}");</script>
    
    <div class="mw-module-images{{ isset($no_img) && $no_img ? ' no-image' : '' }}">
        <div class="mw-pictures-clean" id="mw-gallery-{{ $rand }}">
            @php $count = -1; @endphp
            @foreach($data as $item)
                @php $count++; @endphp
                @if(!isset($item['id']))
                    @continue
                @endif

                <div class="mw-pictures-clean-item mw-pictures-clean-item-{{ $item['id'] }}">
                    <a href="{{ isset($item['filename']) ? $item['filename'] : '' }}"
                       onclick="mw.gallery(gallery{{ $rand }}, {{ $count }});return false;">
                        <img src="{{ thumbnail($item['filename'] ?? '', 600) }}"/>
                    </a>
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
