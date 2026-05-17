{{--
type: layout
name: Slider
description: Pictures slider
--}}

@if(isset($data))
    @php
        $id = "slider-" . uniqid();
    @endphp

    <div class="well mw-module-images slider">
        <div class="mw-rotator mw-rotator-template-slider" id="{{ $id }}">
            <div class="mw-gallery-holder">
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
                        <div class="mw-gallery-item mw-gallery-item-{{ $item['id'] ?? '' }}">
                            {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper.
                                 First slide eager-loaded for LCP per the cycle-41 Slider/default reference. --}}
                            {!! responsive_thumbnail($item['filename'] ?? '', 1200, null, [
                                'alt' => $item['title'] ?? $item['description'] ?? __('Image'),
                                'class' => 'img-fluid',
                                'loading' => $loop->first ? 'eager' : 'lazy',
                            ]) !!}
                            @if(isset($item['title']) && $item['title'] != '')
                                <i class="mw-rotator-description mw-rotator-description-content">{{ $item['title'] }}</i>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <script>
        mw.moduleCSS("{{ asset('modules/pictures/css/slider.css') }}");
        mw.moduleCSS("{{ asset('modules/pictures/css/style.css') }}");
        mw.require("{{ asset('modules/pictures/js/api.js') }}", true);
    </script>

    <script>
        Rotator = null;
        $(document).ready(function () {
            if ($('#{{ $id }}').find('.mw-gallery-item').length > 1) {
                Rotator = mw.rotator('#{{ $id }}');
                if (!Rotator) return false;
                Rotator.options({
                    paging: true,
                    next: true,
                    prev: true
                });
            }
        });
    </script>
@else
    @include('modules.pictures::partials.no-pictures')
@endif
