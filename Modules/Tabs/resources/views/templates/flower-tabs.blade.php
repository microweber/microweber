@php
    /*

    type: layout

    name: Flower tabs

    description: Flower

    */
@endphp

<style>
    .flower-tabs-button {
        border-radius: 100px 0 100px 100px!important;
        background-color: #FED1DC;
        color: #FF2359;
        padding: 30px 65px;
        margin: 0 20px;
        border-color: #FED1DC!important;
    }

    .flower-tabs-button.active {
        background-color: #FF2359;
        color: #ffffff;
    }
</style>

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

<div id="mw-tabs-module-{{ $params['id'] }}" class="mw-tabs-box-wrapper mw-module-tabs-skin-default">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs d-flex justify-content-center float-none">
        @php $count = 0; @endphp
        @if($tabs->isEmpty())
            <p class="mw-pictures-clean">No tabs added to the module. Please add your tabs to see the content.</p>
        @else
            @foreach ($tabs as $slide)
                @php $count++; @endphp
                <a class="flower-tabs-button btn btn-outline-primary my-xl-0 my-3 {{ $count == 1 ? 'active' : '' }}" href="javascript:;">
                    {!! isset($slide['icon']) ? $slide['icon'] . ' ' : '' !!}{{ $slide['title'] ?? '' }}
                </a>
            @endforeach
        @endif
    </div>
    <div class="py-5">
        @php $count = 0; @endphp
        @if($tabs->isEmpty())
            <p class="mw-pictures-clean">No tabs added to the module. Please add your tabs to see the content.</p>
        @else
            @foreach ($tabs as $key => $slide)
                @php
                    $count++;
                    $edit_field_key = $slide['id'] ?? $key;
                @endphp
                <div class="column mw-ui-box-tab-content pt-3 text-center" style="{{ $count != 1 ? 'display: none;' : 'display: block;' }}">
                    <div class="edit" field="tab-item-{{ $edit_field_key }}" rel="module-{{ $params['id'] }}">
                        <div class="element">
                            <h6>
                                {!! $slide['content'] ?? 'Tab content ' . $count . '<P>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</P>' !!}
                            </h6>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
