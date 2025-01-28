@php
    /*

    type: layout

    name: Skin 2

    description: Skin 2

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

    $(document).ready(function () {
        $('.nav-link').on('click', function() {
            // Remove 'active' class from all nav-links
            $('.nav-link').removeClass('active');
            // Add 'active' class to the clicked nav-link
            $(this).addClass('active');
        });
    });
</script>

<style>
    .nav-tabs {
        border-bottom: 0;
    }

    .nav-tabs .nav-link,
    .nav-tabs .nav-link span {
        display: block;
        text-align: left;
    }

    .nav-tabs .nav-link span {
        display: block;
    }

    .nav-tabs .nav-link small {
        display: block;
        font-size: 18px;
        font-weight: normal;
    }

    .nav-tabs .nav-link {
        background-color: #fff;
        border-radius: 0;
        border: 0;
        border-left: 3px solid #ececec;
        padding: 20px 30px;
        transition: all 0.3s;
    }

    .nav-tabs .nav-link:first-child {
        border-left-color: transparent;
    }

    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nav-link.active,
    .nav-tabs .nav-link:focus,
    .nav-tabs .nav-link:hover {
        border-left-color: var(--mw-primary-color);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175);
    }

    .nav-tabs .nav-link.active h4,
    .nav-tabs .nav-link:focus h4,
    .nav-tabs .nav-link:hover h4 {
        color: var(--mw-primary-color);
    }

    .schedule-image {
        border-radius: 20px;
    }

    .mw-ui-btn-nav-tabs {
        float: unset;
    }

    .speakers-text {
        font-size: 14px;
        opacity: .6;
    }

    .avatar-image {
        border: 2px solid #fff;
        border-radius: 100px;
        width: 50px !important;
        height: 50px !important;
        object-fit: cover;
    }

    .avatar-group {
        align-items: center;
    }
</style>

<div id="mw-tabs-module-{{ $params['id'] }}">
    <nav>
        <div class="nav nav-tabs align-items-baseline mw-ui-btn-nav-tabs">
            @php $count = 0; @endphp
            @if($tabs->isEmpty())
                <p class="mw-pictures-clean">No tab items available.</p>
            @else
                @foreach ($tabs as $slide)
                    @php $count++; @endphp
                    <a class="nav-link {{ $count == 1 ? 'active' : '' }} col-lg-3 col-md-6 col-12" href="javascript:;">
                        {!! isset($slide['icon']) ? $slide['icon'] . ' ' : '' !!}
                        <h4 class="header-section-title mb-0">{{ $slide['title'] ?? 'Tab title 1' }}</h4>
                    </a>
                @endforeach
            @endif
        </div>
    </nav>

    @php $count = 0; @endphp
    @if($tabs->isEmpty())
        <p class="mw-pictures-clean">No tab items available.</p>
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
                        <img src="{{ asset('templates/big2/modules/tabs/templates/gallery-1-6.jpg') }}" class="schedule-image img-fluid" alt="">
                    </div>

                    <div class="col-lg-8 col-12 mt-3 mt-lg-0">
                        <h4 class="mb-2">Startup Development Ideas</h4>
                        <div class="small-text">{!! $slide['content'] ?? 'Tab content ' . $count . '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>' !!}</div>
                        <div class="d-flex align-items-center mt-4">
                            <div class="avatar-group d-flex">
                                <img loading="lazy" class="avatar-image" src="{{ asset('templates/big2/resources/assets/img/layouts/1.jpg') }}">
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
