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
                    <p class="mw-pictures-clean">No pictures added. Please add pictures to the module.</p>
                @else
                    @foreach($data as $item)
                        <div class="mw-gallery-item mw-gallery-item-{{ $item['id'] ?? '' }}">
                            {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper.
                                 First slide eager-loaded for LCP per the cycle-41 Slider/default reference. --}}
                            {!! responsive_thumbnail($item['filename'] ?? '', 1200, null, [
                                'alt' => isset($item['title']) ? $item['title'] : __('Image'),
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
