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
            <p>No pictures added. Please add pictures to the gallery.</p>
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
                <img alt="{{ $itemAltText }}" src="{{ isset($item['filename']) ? $item['filename'] : '' }}"/>
            </div>
        @endforeach
    @endif
    </div>
@endif
