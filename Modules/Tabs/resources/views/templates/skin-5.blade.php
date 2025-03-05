@php
    /*

    type: layout

    name: skin-5

    description: skin-5

    */
@endphp

<style>
    .merry-tabs-button {
        background-color: #ffffff!important;
        border-color: #ffffff!important;
        color: #181E4E!important;
        padding: 20px 45px!important;
        margin: 0 20px;
    }

    .merry-navs-btn-pricing {
        border: 1px solid #181E4E;
        padding:  10px 0;
    }

    .merry-tabs-button.active, .merry-tabs-button:hover {
        background-color: #181E4E!important;
        border-color: #181E4E!important;
        color: #ffffff!important;
    }
</style>

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
                {!! isset($slide['icon']) ? $slide['icon'] . ' ' : '' !!}{{ isset($slide['title']) ? $slide['title'] : '' }}
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
