{{--
type: layout
name: Shop Inner
description: Shop inner
--}}

@if(isset($data))
    <div class="elevatezoom">
        <div class="content">
            <div class="elevatezoom-holder">
                @foreach($data as $key => $item)
                    @if($key == 0)
                        <img id="elevatezoom"
                             class="main-image"
                             src="{{ thumbnail($item['filename'] ?? '', 500, 500) }}"
                             data-zoom-image="{{ thumbnail($item['filename'] ?? '', 1920, 1920) }}"/>
                    @endif
                @endforeach
            </div>
        </div>

        <div id="elevatezoom-gallery" class="js-popup-gallery justify-content-center text-center">
            @if(empty($data))
                <p>No pictures added. Please add pictures to the gallery.</p>
            @else
                @foreach($data as $item)
                    <a href="{{ thumbnail($item['filename'] ?? '', 1920, 1920) }}"
                       id="elevatezoom"
                       data-image="{{ thumbnail($item['filename'] ?? '', 800, 800) }}"
                       data-zoom-image="{{ thumbnail($item['filename'] ?? '', 1920, 1920) }}"
                       style="background-image: url('{{ thumbnail($item['filename'] ?? '', 200, 200) }}');">
                    </a>
                @endforeach
            @endif
        </div>
    </div>
@endif
