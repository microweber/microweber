@php
    /*

    type: layout

    name: skin-1

    description: skin-1

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

@if (mw_asset_once('tabs-skin-1'))
    {{-- Skin CSS moved to Modules/Tabs/resources/assets/css/skin-1.css (served from
         public/modules/tabs/css/skin-1.css) and emitted once per page via mw_asset_once
         instead of being re-inlined for every tabs instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/tabs/css/skin-1.css') }}">
@endif

<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#mw-tabs-module-{{ $params['id'] }} .mw-ui-btn-nav-tabs a',
            tabs: '#mw-tabs-module-{{ $params['id'] }} .mw-ui-box-tab-content'
        });
    });
</script>

<div class="mw-tabs-1-wrapper">
    <div id="mw-tabs-module-{{ $params['id'] }}" class="tabs-section w-tabs">
        <div class="tabs-menu w-tab-menu mw-ui-btn-nav-tabs">
            @php $count = 0; @endphp
            @if($tabs->isEmpty())
                <p class="mw-pictures-clean">No tabs added to the module. Please add your tabs to see the content.</p>
            @else
                @foreach ($tabs as $slide)
                    @php $count++; @endphp
                    <a class="tab-link w-inline-block w-tab-link w--current {{ $count == 1 ? 'active' : '' }}" href="javascript:;">
                        {!! isset($slide['icon']) ? icon_html($slide['icon']) . ' ' : '' !!}<span class="mb-0">{{ $slide['title'] ?? 'Tab title 1' }}</span>
                    </a>
                @endforeach
            @endif
        </div>

        <div class="tabs-content w-tab-content">
            <div class="widget-body background-color-element element">
                <div class="tab-box">
                    @php $count = 0; @endphp
                    @if($tabs->isEmpty())
                        <p class="mw-pictures-clean">No tabs added to the module. Please add your tabs to see the content.</p>
                    @else
                         @foreach ($tabs as $key => $slide)
                        @php
                            $count++;
                            $edit_field_key = $slide['id'] ?? $key;
                        @endphp
                        <div class="tabs-content w-tab-content mw-ui-box-tab-content" style="{{ $count != 1 ? 'display: none;' : 'display: block;' }}">
                            <div class="tab-box-contents edit" field="tab-item-{{ $edit_field_key }}" rel="module-{{ $params['id'] }}">
                                <img loading="lazy" class="rounded-image tab-image" src="{{ template_url('resources/assets/img/layouts/gallery-1-6.jpg') }}" alt="{{ __('Gallery image') }}" />
                                <div class="widget message tab-widget">
                                    <div class="widget-body">
                                        <div class="widget-chat-top">
                                            <div class="widget-person">
                                                <img loading="lazy" src="https://assets-global.website-files.com/65cc2f9d76ead6a81505ea44/65cc3c1ab8ec74c43d57835d_photo-avatar-04_compressed.webp" alt="" class="widget-chat-avatar">
                                                <div class="small-text text-bold">Lisa Stein</div>
                                            </div>
                                            <div class="small-text">5 mins</div>
                                        </div>
                                        <div class="small-text">{!! $slide['content'] ?? 'Tab content ' . $count . '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>' !!}</div>
                                        <module type="btn" button_style="btn-link" button_text="Read more" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
