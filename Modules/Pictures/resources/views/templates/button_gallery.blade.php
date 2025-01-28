{{--
type: layout
name: Button gallery
description: Button gallery
--}}

@if(isset($data))
    @php
        $rand = uniqid();
    @endphp

    <div class="mw-module-images{{ isset($no_img) && $no_img ? ' no-image' : '' }}">
        <div class="mw-pictures-clean" id="mw-gallery-{{ $rand }}">
            @php $count = -1; @endphp
            @if(empty($data))
                <p class="mw-pictures-clean">No pictures added. Please add pictures to the gallery.</p>
            @else
                @foreach($data as $item)
                @php $count++; @endphp
                @if($count == 1)
                    <a href="{{ isset($item['filename']) ? $item['filename'] : '' }}" onclick="mw.gallery(gallery{{ $rand }}, {{ $count }});return false;" class="btn btn-default">{{ _e("View photos") }}</a>
                @endif
            @endforeach
            @endif

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
