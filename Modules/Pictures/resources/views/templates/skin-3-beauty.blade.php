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
                    <p class="mw-pictures-clean">No pictures added. Please add pictures to the module.</p>
                @else
                    @foreach($data as $count => $item)
                        @if($count == 0)
                            <div class="item pictures picture-{{ $item['id'] ?? '' }}"
                                 data-mw-gallery="@php echo base64_encode(json_encode(array_map(function ($it) { return ['image' => $it['filename'] ?? '', 'description' => $it['title'] ?? '']; }, $data ?? []))); @endphp" data-mw-gallery-index="{{ $count }}">
                                {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                {!! responsive_thumbnail($item['filename'] ?? '', 1400, 1400, ['class' => 'img-fluid', 'crop' => true]) !!}
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="col-holder col-md-6">
                @foreach($data as $count => $item)
                    @if($count == 1 || $count == 2)
                        <div class="item pictures picture-{{ $item['id'] ?? '' }}"
                             data-mw-gallery="@php echo base64_encode(json_encode(array_map(function ($it) { return ['image' => $it['filename'] ?? '', 'description' => $it['title'] ?? '']; }, $data ?? []))); @endphp" data-mw-gallery-index="{{ $count }}">
                            {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                            {!! responsive_thumbnail($item['filename'] ?? '', 1400, 695, ['class' => 'img-fluid', 'crop' => true]) !!}
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
