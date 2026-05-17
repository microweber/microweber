{{--
type: layout
name: Blog pro
description: Blog pro
--}}

<style>
    .card-header-single img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100%;
        object-fit: cover;
        object-position: center top;
    }

    .card-header-archive, .card-header-single {
        position: relative;
        padding-top: 70% !important;
    }
</style>

@if(isset($data))
    @php
        $rand = uniqid();
        $click_image_event = 'fullscreen';
        $get_click_image_event = get_option('click_image_event', $params['id'] ?? null);
        if ($get_click_image_event != false) {
            $click_image_event = $get_click_image_event;
        }
    @endphp

    <div class="">
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
            @php
                $itemTitle = false;
                $itemDescription = false;
                $itemLink = false;
                $itemAltText = 'Open';
                if (isset($item['image_options']) && is_array($item['image_options'])) {
                    if (isset($item['image_options']['title'])) {
                        $itemTitle = $item['image_options']['title'];
                    }
                    if (isset($item['image_options']['caption'])) {
                        $itemDescription = $item['image_options']['caption'];
                    }
                    if (isset($item['image_options']['link'])) {
                        $itemLink = $item['image_options']['link'];
                    }
                    if (isset($item['image_options']['alt-text'])) {
                        $itemAltText = $item['image_options']['alt-text'];
                    }
                }
            @endphp

            <div class="card-header-single">
                {!! responsive_thumbnail($item['filename'] ?? '', 800, null, ['alt' => $itemAltText, 'class' => 'img-fluid']) !!}
            </div>
        @endforeach
    @endif
    </div>
@endif
