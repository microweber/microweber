{{--
type: layout
name: Button gallery
description: Button gallery
--}}

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

    <div class="mw-module-images{{ isset($no_img) && $no_img ? ' no-image' : '' }}">
        <div class="mw-pictures-clean" id="mw-gallery-{{ $rand }}">
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
                @if($count == 1)
                    <a href="{{ isset($item['filename']) ? $item['filename'] : '' }}" data-mw-gallery="{{ $mwGalleryGalleryJson }}" data-mw-gallery-index="{{ $count }}" class="btn btn-default">{{ _e("View photos") }}</a>
                @endif
            @endforeach
            @endif

            <script>
                (function () {
                    window.gallery{{ $rand }} = @php echo json_encode(array_map(function ($item) { return ['image' => $item['filename'] ?? '', 'description' => $item['title'] ?? '']; }, $data ?? []), JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS); @endphp;
                })();
            </script>
        </div>
    </div>
@else
    @include('modules.pictures::partials.no-pictures')
@endif
