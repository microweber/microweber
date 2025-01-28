@php
    /*

    type: layout

    name: Skin 3

    description: Skin 3

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

<div id="mw-tabs-module-{{ $params['id'] }}" class="mw-tabs-box-wrapper mw-module-tabs-skin-default">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs df">
        @php $count = 0; @endphp
        @if($tabs->isEmpty())
            <p class="mw-pictures-clean">No tab items available.</p>
        @else
            @foreach ($tabs as $slide)
                @php $count++; @endphp
                <a class="btn btn-primary df mb-3 {{ $count == 1 ? 'active' : '' }}" href="javascript:;">
                    {!! isset($slide['icon']) ? $slide['icon'] . ' ' : '' !!}<span class="mb-0">{{ $slide['title'] ?? 'Tab title 1' }}</span>
                </a>
            @endforeach
        @endif
    </div>
    <div class="mw-ui-box">
        @php $count = 0; @endphp
        @if($tabs->isEmpty())
            <p class="mw-pictures-clean">No tab items available.</p>
        @else
            @foreach ($tabs as $key => $slide)
                @php
                    $count++;
                    $edit_field_key = $slide['id'] ?? $key;
                @endphp
                <div class="mw-ui-box-content mw-ui-box-tab-content" style="{{ $count != 1 ? 'display: none;' : 'display: block;' }}">
                    <div class="edit" field="tab-item-{{ $edit_field_key }}" rel="module-{{ $params['id'] }}">
                        <h6>{!! $slide['content'] ?? 'Tab content ' . $count . '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>' !!}</h6>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
