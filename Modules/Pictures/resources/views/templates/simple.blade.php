{{--
type: layout
name: Simple
description: Simple Pictures List Template
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

    <script>mw.moduleCSS("{{ asset('modules/pictures/css/style.css') }}");</script>

    <div class="mw-module-images{{ isset($no_img) && $no_img ? ' no-image' : '' }}">
        <div class="mw-pictures-list mw-images-template-default-grid" id="mw-gallery-{{ $rand }}">
            @php $count = -1; @endphp
            @if(empty($data))

                @if (is_live_edit())
                    <div class="mw-canvas-empty-state" data-mw-content-type="picture">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                    </div>
                @endif
            @else
                @foreach($data as $item)
                    @php $count++; $cropPos = $item['crop_position'] ?? ''; @endphp
                    <div class="mw-pictures-item mw-pictures-item-{{ $item['id'] ?? '' }}">
                        <div class="thumbnail"
                             data-mw-gallery="{{ $mwGalleryGalleryJson }}" data-mw-gallery-index="{{ $count }}">
                            <span class="pic-valign">
                                <span class="pic-valign-cell">

                                    {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                    {!! responsive_thumbnail($item['filename'] ?? '', 300, null, array_filter([
                                        'alt' => $item['title'] ?? $item['description'] ?? __('Image'),
                                        'class' => 'img-fluid',
                                        'style' => $cropPos !== '' ? 'object-position: ' . $cropPos : '',
                                    ])) !!}
                                </span>
                            </span>
                        </div>
                    </div>
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
