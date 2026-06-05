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
    <div class="gallery-holder">
        <div class="row">
            <div class="col-holder col-md-6">
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
                    @foreach($data as $count => $item)
                        @if($count == 0)
                            <div class="item pictures picture-{{ $item['id'] ?? '' }}"
                                 data-mw-gallery="{{ $mwGalleryGalleryJson }}" data-mw-gallery-index="{{ $count }}">
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
                             data-mw-gallery="{{ $mwGalleryGalleryJson }}" data-mw-gallery-index="{{ $count }}">
                            {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                            {!! responsive_thumbnail($item['filename'] ?? '', 1400, 695, ['class' => 'img-fluid', 'crop' => true]) !!}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

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
@endif
