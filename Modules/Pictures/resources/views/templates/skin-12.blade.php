{{--
type: layout
name: Skin-12
description: Skin-12
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
            @php $count = -1; @endphp
            @foreach($data as $item)
                @php $count++; @endphp
                @if($count == 0 || $count == 5)
                    <div class="col-holder col-8">
                        <div class="item pictures picture-{{ $item['id'] ?? '' }}"
                             onclick="mw.gallery(gallery{{ $rand }}, {{ $count }});return false;">
                            <img class="w-100"
                                 src="{{ thumbnail($item['filename'] ?? '', 800, 800, true) }}"
                                 alt="">
                        </div>
                    </div>
                @else
                    @if($count == 1 || $count == 3)
                        <div class="col-holder col-4">
                    @endif

                    <div class="item pictures picture-{{ $item['id'] ?? '' }}"
                         onclick="mw.gallery(gallery{{ $rand }}, {{ $count }});return false;">
                        <img class="w-100"
                             src="{{ thumbnail($item['filename'] ?? '', 500, 500, true) }}"
                             alt="">
                    </div>

                    @if($count == 2 || $count == 4)
                        </div>
                    @endif
                @endif

                @if($count == 5)
                    @php $count = -1; @endphp
                @endif
            @endforeach
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
