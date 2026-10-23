{{--
type: layout
name: Shop Inner
description: Shop inner
--}}

@if(isset($data))
    <div class="elevatezoom">
        <div class="content">
            <div class="elevatezoom-holder">
                @foreach($data as $key => $item)
                    @if($key == 0)
                    @php $cropPos = $item['crop_position'] ?? ''; @endphp
<img id="elevatezoom"
                         class="main-image"
                         src="{{ thumbnail($item['filename'] ?? '', 500, 500) }}"
                         data-zoom-image="{{ thumbnail($item['filename'] ?? '', 1920, 1920) }}"
                         @if($cropPos !== '') style="object-position: {{ $cropPos }}" @endif
                         alt="{{ __('Product image') }}"/>
                    @endif
                @endforeach
            </div>
        </div>

        <div id="elevatezoom-gallery" class="js-popup-gallery justify-content-center text-center">
            @if(empty($data))

                @if (is_live_edit())
                    <div class="mw-canvas-empty-state" data-mw-content-type="picture">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                    </div>
                @endif
            @else
                {{-- audit-test 2026-05-07 PM TICKET-AV bundle: `<a style="background-image: url(...)">`
                     was a CSS-injection sink; migrated to `<a><img>`. --}}
                @foreach($data as $item)
                    <a href="{{ thumbnail($item['filename'] ?? '', 1920, 1920) }}"
                       id="elevatezoom"
                       data-image="{{ thumbnail($item['filename'] ?? '', 800, 800) }}"
                       data-zoom-image="{{ thumbnail($item['filename'] ?? '', 1920, 1920) }}">
                        {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                        {!! responsive_thumbnail($item['filename'] ?? '', 200, 200, [
                            'alt' => $item['title'] ?? $item['description'] ?? __('Product image'),
                            'class' => 'img-fluid d-block',
                        ]) !!}
                    </a>
                @endforeach
            @endif
        </div>
    </div>
@endif
