{{--
type: layout
name: Button gallery
description: Button gallery
--}}

@if(isset($data))
    @php
        $rand = uniqid();
        // task-2026-05-17-8cf71e / AI-814 — precompute gallery JSON
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
                {{-- task-2026-05-17-525769 / AI-812 — wrap admin-targeted empty-state copy in is_admin() gate;
                     adopt AI-780a typed empty-state pattern (heading + body + CTA pointing to admin_url('media')).
                     Pre-fix the bare <p> rendered "No pictures added. Please add pictures to the module." to
                     anonymous frontend visitors — admin-targeted copy leaked to public surface. --}}
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

            {{-- task-2026-05-17-8cf71e / AI-814 — IIFE wrapper for scope
                 isolation + json_encode with JS-context escape flags
                 (HEX_QUOT/TAG/AMP/APOS) so title/description containing JS-
                 meaningful characters (", \, /, newline, etc.) cannot break
                 out of the string context. Pre-fix the description was
                 interpolated via Blade {{}} into a JS-string literal —
                 HTML-escape only, not JS-escape (defense-in-depth XSS fix).
                 window.gallery<rand> assignment preserved so external readers
                 accessing the global by name still find it. --}}
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
