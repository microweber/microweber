<div class="d-flex align-items-center">

    @php
    $showBackButton = true;
    if (isset($isIframe) && $isIframe) {
        $showBackButton = false;
    }
    @endphp

    @if ($showBackButton)
    <div class="mw-toolbar-back-button-wrapper me-3">
        <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3 d-flex align-items-center" id="mw-modules-toolbar" style="">
            <div>
                <a href="{{route('admin.'.$content_type.'.index')}}">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"></path></svg>
                </a>
            </div>
        </div>
    </div>
    <div class="mb-3 d-flex align-items-center">
        <a class="tblr-body-color form-label mb-0 text-decoration-none font-weight-bold d-md-block d-none" href="{{route('admin.'.$content_type.'.index')}}" class="mb-0">
            @if($content_id > 0)
                {{ _e("Edit " . ucfirst($content_type)) }}
            @else
                {{ _e("Add " . ucfirst($content_type)) }}
            @endif
        </a>
        <span class="tblr-body-color form-label mb-0 font-weight-bold ms-1 d-lg-block d-none">
           @if($content_id > 0)
                / {{ content_title($content_id) }}
            @endif
        </span>
    </div>
    @endif

</div>
