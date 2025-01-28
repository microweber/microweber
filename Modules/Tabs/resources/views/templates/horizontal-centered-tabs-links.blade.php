@php
    /*

    type: layout

    name: horizontal centered tabs links

    description: Horizontal

    */
@endphp

@php
    if ($tabs == false) {
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
</script>

<style>
    .mw-module-tabs-skin-horizontal-links {
        position: sticky;
        top: 0;
        z-index: 9;
        backdrop-filter: saturate(180%) blur(20px);
    }

    .mw-module-tabs-skin-horizontal-links ul > li > a {
        color: var(--mw-heading-color);
        display: inline-block;
        line-height: 50px;
        position: relative;
        transition: .3s;
    }

    .mw-module-tabs-skin-horizontal-links ul {
        list-style: none;
    }

    .mw-module-tabs-skin-horizontal-links ul > li {
        position: relative;
    }

    .mw-module-tabs-skin-horizontal-links ul > li > a span:after {
        position: absolute;
        content: '';
        bottom: 10px;
        left: 0;
        height: 2px;
        width: 70%;
        background-color: var(--mw-primary-color);
        transition: .5s;
        opacity: 0;
    }

    .mw-module-tabs-skin-horizontal-links ul > li > a:hover, .mw-module-tabs-skin-horizontal-links ul > li a.active {
        color: var(--mw-primary-color);
        opacity: .8;
    }

    .mw-module-tabs-skin-horizontal-links ul > li > a:hover span:after, .mw-module-tabs-skin-horizontal-links ul > li a.active span:after {
        width: 100%;
        opacity: 1;
    }

    .mw-module-tabs-skin-horizontal-links ul > li > a {
        font-size: var(--mw-paragraph-size);
        font-weight: var(--mw-font-weight);
    }
</style>

<div id="mw-tabs-module-{{ $params['id'] }}" class="row mw-tabs-box-wrapper mw-module-tabs-skin-horizontal-links">
    <div class="mw-ui-btn-nav merry-navs-btn-pricing mw-ui-btn-nav-tabs d-flex justify-content-center flex-wrap mx-auto mt-5 gap-2">
        @php $count = 0; @endphp
        @if($tabs->isEmpty())
            <p class="mw-pictures-clean">No tabs added to the module. Please add your tabs to see the content.</p>
        @else
            @foreach ($tabs as $slide)
                @php $count++; @endphp
                <ul class="ps-0">
                    <li>
                        <a class="btn btn-link my-xl-0 my-3 {{ $count == 1 ? 'active' : '' }}" href="javascript:;">
                            {!! isset($slide['icon']) ? $slide['icon'] . ' ' : '' !!}<span>{{ $slide['title'] ?? '' }}</span>
                        </a>
                    </li>
                </ul>
            @endforeach
        @endif
    </div>
    <div>
        @php $count = 0; @endphp
        @if($tabs->isEmpty())
            <p class="mw-pictures-clean">No tabs added to the module. Please add your tabs to see the content.</p>
        @else
            @foreach ($tabs as $key => $slide)
            @php
                $count++;
                $edit_field_key = $slide['id'] ?? $key;
            @endphp
            <div class="column mw-ui-box-tab-content pt-3" style="{{ $count != 1 ? 'display: none;' : 'display: block;' }}">
                <div class="edit" field="tab-item-{{ $edit_field_key }}" rel="module-{{ $params['id'] }}">
                    <div class="element">
                        <h6>
                            {!! $slide['content'] ?? '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>' !!}
                        </h6>
                    </div>
                </div>
            </div>
        @endforeach
        @endif
    </div>
</div>
