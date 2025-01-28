{{--
type: layout
name: Skin-1
description: Skin-1
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
            <p class="mw-pictures-clean">No pictures added. Please add pictures to the gallery.</p>
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

            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                <div class="d-block position-relative show-on-hover-root">
                    <div class="img-as-background mh-400 mb-3">
                        <img alt="{{ $itemAltText }}"
                             src="{{ thumbnail($item['filename'] ?? '', 1080, 1080, true) }}"/>
                    </div>

                    @if($itemTitle || $itemDescription || $itemLink)
                        <div class="show-on-hover position-absolute bg-body border mh-400 w-100 top-0 mb-3 p-5 text-center align-items-center justify-content-center d-flex flex-column">
                            @if($itemTitle)
                                <h4 class="mb-1">{{ $itemTitle }}</h4>
                            @endif
                            @if($itemDescription)
                                <p class="mb-3">{{ $itemDescription }}</p>
                            @endif
                            @if($itemLink)
                                <a @if($click_image_event == 'link_target_blank') target="_blank" @endif
                                   href="{{ $itemLink }}"
                                   class="btn btn-link">{{ $itemAltText }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
        @endif
    </div>
@endif
