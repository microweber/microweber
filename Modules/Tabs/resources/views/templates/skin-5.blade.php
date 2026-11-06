@php
    /*

    type: layout

    name: skin-5

    description: skin-5

    */
@endphp

@if (mw_asset_once('tabs-skin-5'))
    {{-- Skin CSS moved to Modules/Tabs/resources/assets/css/skin-5.css (served from
         public/modules/tabs/css/skin-5.css) and emitted once per page via mw_asset_once
         instead of being re-inlined for every instance of this skin. --}}
    <link rel="stylesheet" href="{{ asset('modules/tabs/css/skin-5.css') }}">
@endif

@php
    if (!isset($tabs) || empty($tabs)) {
        echo lnotif(_e('Click to edit tabs', true));
        return;
    }


@endphp

<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#mw-tabs-module-{{ $params['id'] }} .mw-ui-btn-nav-tabs a',
            tabs: '#mw-tabs-module-{{ $params['id'] }} .mw-ui-box-tab-content'
        });
    });
</script>

<div id="mw-tabs-module-{{ $params['id'] }}"
     class="row mw-tabs-box-wrapper mw-module-tabs-skin-default">
    <div class="mw-ui-btn-nav merry-navs-btn-pricing mw-ui-btn-nav-tabs d-flex flex-wrap col-md-3 mx-auto mt-5 col-12 justify-content-center">
        @php $count = 0; @endphp
        @foreach ($tabs as $slide)
            @php $count++; @endphp
            <a class="merry-tabs-button btn btn-outline-primary my-xl-0 my-3 @if ($count == 1) active @endif" href="javascript:;">
                {!! isset($slide['icon']) ? icon_html($slide['icon']) . ' ' : '' !!}{{ isset($slide['title']) ? $slide['title'] : '' }}
            </a>
        @endforeach
    </div>
    <div class="py-5">
        @php $count = 0; @endphp
        @foreach ($tabs as $key => $slide)
            @php
                $count++;
                $edit_field_key = isset($slide['id']) ? $slide['id'] : $key;
            @endphp
            <div class="column mw-ui-box-tab-content pt-3 text-center" style="@if ($count != 1) display: none; @else display: block; @endif">
                <div class="edit allow-drop" field="tab-item-{{ $edit_field_key }}" rel="module-{{ $params['id'] }}">
                    <div class="element">
                        <br>
                        <module type="layouts" template="price_lists/skin-10"/>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
