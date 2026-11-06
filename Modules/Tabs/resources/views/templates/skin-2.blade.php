@php
    /*

    type: layout

    name: Skin 2

    description: Skin 2

    */
@endphp

@php
      if (!isset($tabs) || empty($tabs)) {
        echo lnotif(_e('Click to edit tabs', true));
        return;
    }

//    if (!isset($tabs) || count($tabs) == 0) {
//        $tabs = $defaults;
//    }
@endphp

<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#mw-tabs-module-{{ $params['id'] }} .mw-ui-btn-nav-tabs a',
            tabs: '#mw-tabs-module-{{ $params['id'] }} .mw-ui-box-tab-content'
        });
    });

    $(document).ready(function () {
        $('.nav-link').on('click', function() {
            // Remove 'active' class from all nav-links
            $('.nav-link').removeClass('active');
            // Add 'active' class to the clicked nav-link
            $(this).addClass('active');
        });
    });
</script>

@if (mw_asset_once('tabs-skin-2'))
    {{-- Skin CSS moved to Modules/Tabs/resources/assets/css/skin-2.css (served from
         public/modules/tabs/css/skin-2.css) and emitted once per page via mw_asset_once
         instead of being re-inlined for every instance of this skin. --}}
    <link rel="stylesheet" href="{{ asset('modules/tabs/css/skin-2.css') }}">
@endif

<div id="mw-tabs-module-{{ $params['id'] }}">
    <nav>
        <div class="nav nav-tabs align-items-baseline mw-ui-btn-nav-tabs">
            @php $count = 0; @endphp
            @if($tabs->isEmpty())
                <p class="mw-pictures-clean">No tabs added to the module. Please add your tabs to see the content.</p>
            @else
                @foreach ($tabs as $slide)
                    @php $count++; @endphp
                    <a class="nav-link {{ $count == 1 ? 'active' : '' }} col-lg-3 col-md-6 col-12" href="javascript:;">
                        {!! isset($slide['icon']) ? icon_html($slide['icon']) . ' ' : '' !!}
                        <h4 class=" mb-0">{{ $slide['title'] ?? 'Tab title 1' }}</h4>
                    </a>
                @endforeach
            @endif
        </div>
    </nav>

    @php $count = 0; @endphp
    @if($tabs->isEmpty())
        <p class="mw-pictures-clean">No tabs added to the module. Please add your tabs to see the content.</p>
    @else
        @foreach ($tabs as $key => $slide)
        @php
            $count++;
            $edit_field_key = $slide['id'] ?? $key;
        @endphp
        <div class="tab-content mt-5 mw-ui-box-tab-content safe-mode" style="{{ $count != 1 ? 'display: none;' : 'display: block;' }}">
            <div class="edit safe-mode" field="tab-item-{{ $edit_field_key }}" rel="module-{{ $params['id'] }}">
                <div class="row border-bottom safe-mode cloneable element pb-5 mb-5">
                    <div class="col-lg-4 col-12">
                        <img src="{{ asset('templates/big2/img/layouts/gallery-1-6.jpg') }}" class="schedule-image img-fluid" alt="">
                    </div>

                    <div class="col-lg-8 col-12 mt-3 mt-lg-0">
                        <h4 class="mb-2">Startup Development Ideas</h4>
                        <div class="small-text">{!! $slide['content'] ?? 'Tab content ' . $count . '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>' !!}</div>
                        <div class="d-flex align-items-center mt-4">
                            <div class="avatar-group d-flex">
                                <img loading="lazy" class="avatar-image" src="{{ asset('templates/big2/img/layouts/events/1.jpg') }}" alt="{{ __('Event image') }}">
                                <div class="ms-3">
                                    Logan Wilson
                                    <p class="speakers-text mb-0">CEO / Founder</p>
                                </div>
                            </div>
                            <span class="mx-3 mx-lg-5">
                                <i class="mw-micon-Clock-Forward me-2"></i>
                                9:00 - 9:45 AM
                            </span>
                            <span class="mx-1 mx-lg-5">
                                <i class="mw-micon-Calendar-4 me-2"></i>
                                Conference Hall 3
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @endif
</div>
