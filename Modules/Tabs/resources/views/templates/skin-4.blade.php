@php
    /*

    type: layout

    name: skin-4

    description: skin-4

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
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs justify-content-center float-none">
        @php $count = 0; @endphp
        @if($tabs->isEmpty())
            <p class="mw-pictures-clean">No tab items available.</p>
        @else
            @foreach ($tabs as $slide)
                @php $count++; @endphp
                <a class="btn btn-outline-primary px-5 {{ $count == 1 ? 'active' : '' }}" href="javascript:;">
                    {!! isset($slide['icon']) ? $slide['icon'] . ' ' : '' !!}{{ $slide['title'] ?? '' }}
                </a>
            @endforeach
        @endif
    </div>
    <div class="py-5">
        @php $count = 0; @endphp
        @if($tabs->isEmpty())
            <p class="mw-pictures-clean">No tab items available.</p>
        @else
            @foreach ($tabs as $key => $slide)
                @php
                    $count++;
                    $edit_field_key = $slide['id'] ?? $key;
                @endphp
                <div class="column mw-ui-box-tab-content pt-3 text-center" style="{{ $count != 1 ? 'display: none;' : 'display: block;' }}">
                    <div class="edit" field="tab-item-{{ $edit_field_key }}" rel="module-{{ $params['id'] }}">
                        <div class="element">
                            <h6>{!! $slide['content'] ?? 'Tab content ' . $count !!}</h6>
                            <br>
                            <p>
                                It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
