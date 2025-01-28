{{--
type: layout
name: Skin-3 beauty
description: Skin-3 beauty
--}}

<style>
    #{{ $params['id'] ?? '' }} .gallery-holder .col-holder {
        padding-right: 4px;
        padding-left: 4px;
    }

    #{{ $params['id'] ?? '' }} .gallery-holder .row {
        margin-right: -4px;
        margin-left: -4px;
    }

    #{{ $params['id'] ?? '' }} .gallery-holder .item {
        margin-bottom: 8px;
    }
</style>

@if(isset($data))
    @php
        $rand = uniqid();
    @endphp
    <div class="gallery-holder">
        <div class="row">
            <div class="col-holder col-md-6">
                @if(empty($data))
                    <p class="mw-pictures-clean">No pictures added. Please add pictures to the gallery.</p>
                @else
                    @foreach($data as $count => $item)
                        @if($count == 0)
                            <div class="item pictures picture-{{ $item['id'] ?? '' }}"
                                 onclick="mw.gallery(gallery{{ $rand }}, {{ $count }});return false;">
                                <img src="{{ thumbnail($item['filename'] ?? '', 1400, 1400, true) }}" alt="">
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="col-holder col-md-6">
                @foreach($data as $count => $item)
                    @if($count == 1 || $count == 2)
                        <div class="item pictures picture-{{ $item['id'] ?? '' }}"
                             onclick="mw.gallery(gallery{{ $rand }}, {{ $count }});return false;">
                            <img src="{{ thumbnail($item['filename'] ?? '', 1400, 695, true) }}" alt="">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <script>
        gallery{{ $rand }} = [
            @foreach($data as $item)
                {
                    image: "{{ thumbnail($item['filename'] ?? '', 1200) }}",
                    description: "{{ $item['title'] ?? '' }}"
                },
            @endforeach
        ];
    </script>
@endif
