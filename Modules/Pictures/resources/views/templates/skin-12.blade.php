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

            @if(empty($data))
                {{-- task-2026-05-17-525769 / AI-812 — wrap admin-targeted empty-state copy in is_admin() gate;
                     adopt AI-780a typed empty-state pattern (heading + body + CTA pointing to admin_url('media')).
                     Pre-fix the bare <p> rendered "No pictures added. Please add pictures to the module." to
                     anonymous frontend visitors — admin-targeted copy leaked to public surface. --}}
                @if (is_admin())
                    <div class="mw-canvas-empty-state" data-mw-ai780-content-type="picture">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                    </div>
                @endif
            @else
                @foreach($data as $item)
                @php $count++; @endphp
                @if($count == 0 || $count == 5)
                    <div class="col-holder col-8">
                        <div class="item pictures picture-{{ $item['id'] ?? '' }}"
                             data-mw-gallery="@php echo base64_encode(json_encode(array_map(function ($it) { return ['image' => $it['filename'] ?? '', 'description' => $it['title'] ?? '']; }, $data ?? []))); @endphp" data-mw-gallery-index="{{ $count }}">
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
                         data-mw-gallery="@php echo base64_encode(json_encode(array_map(function ($it) { return ['image' => $it['filename'] ?? '', 'description' => $it['title'] ?? '']; }, $data ?? []))); @endphp" data-mw-gallery-index="{{ $count }}">
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
            @endif
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
