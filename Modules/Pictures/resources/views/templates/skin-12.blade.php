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
        // ONCE outside any @foreach loop. Pre-fix the same
        // base64+json_encode ran N times for an N-image gallery
        // because the encode call lived inside data-mw-gallery=
        // attribute on the looped element. Move out for perf +
        // hoist for clarity.
        $mwGalleryGalleryJson = base64_encode(json_encode(array_map(function ($item) {
            return ['image' => $item['filename'] ?? '', 'description' => $item['title'] ?? ''];
        }, $data ?? [])));
    @endphp
    <div class="gallery-holder">
        <div class="row">
            @php $count = -1; @endphp

            @if(empty($data))

                @if (is_admin())
                    <div class="mw-canvas-empty-state" data-mw-content-type="picture">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                    </div>
                @endif
            @else
                @foreach($data as $item)
                @php $count++; @endphp
                @php $cropPos = $item['crop_position'] ?? ''; @endphp
                @if($count == 0 || $count == 5)
                    <div class="col-holder col-8">
                        <div class="item pictures picture-{{ $item['id'] ?? '' }}"
                             data-mw-gallery="{{ $mwGalleryGalleryJson }}" data-mw-gallery-index="{{ $count }}">
                            <img class="w-100"
                                 src="{{ thumbnail($item['filename'] ?? '', 800, 800, true) }}"
                                 @if($cropPos) style="object-position: {{ $cropPos }}" @endif
                                 alt="">
                        </div>
                    </div>
                @else
                    @if($count == 1 || $count == 3)
                        <div class="col-holder col-4">
                    @endif

                    <div class="item pictures picture-{{ $item['id'] ?? '' }}"
                         data-mw-gallery="{{ $mwGalleryGalleryJson }}" data-mw-gallery-index="{{ $count }}">
                        <img class="w-100"
                             src="{{ thumbnail($item['filename'] ?? '', 500, 500, true) }}"
                             @if($cropPos) style="object-position: {{ $cropPos }}" @endif
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
        (function () {
            window.gallery{{ $rand }} = @php echo json_encode(array_map(function ($item) { return ['image' => $item['filename'] ?? '', 'description' => $item['title'] ?? '']; }, $data ?? []), JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS); @endphp;
        })();
    </script>
@endif
