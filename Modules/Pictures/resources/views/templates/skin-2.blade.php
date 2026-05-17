{{--
type: layout
name: Skin-2
description: Skin-2
--}}

@if(isset($data))
    @php
        $rand = uniqid();
        $click_image_event = 'fullscreen';
        $get_click_image_event = get_option('click_image_event', $params['id'] ?? null);
        if ($get_click_image_event != false) {
            $click_image_event = $get_click_image_event;
        }
    @endphp

    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center">
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

            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="d-block position-relative show-on-hover-root">
                    <div class="img-as-background mh-350 mb-3">
                        {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                        {!! responsive_thumbnail($item['filename'] ?? '', 350, 350, ['alt' => __('Image'), 'class' => 'img-fluid', 'crop' => true]) !!}
                    </div>

                    @if($itemTitle || $itemDescription || $itemLink)
                        <div class="show-on-hover position-absolute bg-body border mh-350 w-100 top-0 mb-3 p-5 text-center align-items-center justify-content-center d-flex flex-column">
                            @if($itemTitle)
                                <h4 class="mb-1">{{ $itemTitle }}</h4>
                            @endif
                            @if($itemDescription)
                                <p class="mb-3">{{ $itemDescription }}</p>
                            @endif

                            @if($itemLink)
                                <a @if($click_image_event == 'link_target_blank') target="_blank" @endif
                                   href="{{ $itemLink }}"
                                   class="btn btn-link" rel="noopener noreferrer">{{ $itemAltText }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
       @endif
    </div>
@endif
