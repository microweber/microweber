@php
    /*

    type: layout

    name: skin-1

    description: skin-1

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

<style>
    .mw-tabs-1-wrapper {
        .w-tab-link, .w-tab-menu, .w-tabs {
            position: relative;
        }

        .w-tab-link {
            cursor: pointer;
            text-align: left;
            text-decoration: none;
            vertical-align: top;
        }

        .w-tab-content {
            display: block;
            overflow: hidden;
            position: relative;
        }

        .w-tab-pane {
            display: none;
            position: relative;
        }

        .w--tab-active {
            display: block;
        }

        .small-text {
            font-size: 14px;
            line-height: 1.35em;
        }

        .button {
            align-items: center;
            border-image: none 100% 1 0 stretch;
            border-radius: 7px;
            border-style: solid;
            border-width: 1px;
            color: #fff;
            display: flex;
            font-variation-settings: "wght" 550;
            justify-content: center;
            text-align: center;
            transition: border-color .2s, background-color .2s, filter .2s;
            transition-behavior: normal, normal, normal;
        }

        .heading-four {
            font-size: 23px;
            line-height: 1.3em;
        }

        .heading-four, .text-bold {
            color: #222;
            font-variation-settings: "wght" 600;
        }

        .rounded-image {
            border-radius: 10px;
            display: block;
        }

        .align-center {
            margin-left: auto;
            margin-right: auto;
        }

        .widget {
            background-color: #fff;
            border: 1px solid rgba(34, 34, 34, .1);
            border-image: none 100% 1 0 stretch;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, .09) 0 1px 4px;
        }

        .widget-body, .widget-profile-author {
            column-gap: 18px;
            display: flex;
            row-gap: 18px;
        }

        .widget-body {
            flex: 1;
            flex-direction: column;
            padding: 18px;
            text-align: left;
        }

        .widget-profile-author {
            align-items: center;
        }

        .widget-profile-avatar {
            max-height: 72px;
        }

        .widget-profile-name, .widget-rows {
            column-gap: 2px;
            display: flex;
            flex-direction: column;
            row-gap: 2px;
        }

        .widget-rows {
            column-gap: 9px;
            row-gap: 9px;
        }

        .widget-justified {
            align-items: center;
            display: flex;
            justify-content: space-between;
        }

        .widget-profile-rating {
            align-items: center;
            column-gap: 6px;
            display: flex;
            row-gap: 6px;
        }

        .widget-placeholder-text {
            background-color: rgba(34, 34, 34, .1);
            border-radius: 100px;
            height: 8px;
            width: 100px;
        }

        .widget-chat-top {
            align-items: center;
            display: flex;
            justify-content: space-between;
        }

        .widget-person {
            align-items: center;
            column-gap: 12px;
            display: flex;
            row-gap: 12px;
        }

        .widget-chat-avatar {
            max-height: 36px;
        }

        .tabs-section {
            align-items: center;
            align-self: stretch;
            display: flex;
        }

        .tabs-content {
            flex: 1;
        }

        .tab-box, .tabs-menu {
            align-items: center;
            display: flex;
            flex-direction: column;
        }

        .tabs-menu {
            column-gap: 18px;
            flex: 1;
            row-gap: 18px;
        }

        .tab-box {
            background-color: var(--mw-primary-color);
            border-radius: 10px;
            height: 640px;
            justify-content: center;
            padding-bottom: 72px;
            padding-top: 72px;
        }

        .tab-link {
            background-color: transparent;
            border: 1px solid transparent;
            border-radius: 10px;
            color: rgba(34, 34, 34, .8);
            column-gap: 12px;
            font-variation-settings: "wght" 450;
            font-weight: 400;
            max-width: 441px;
            padding: 18px;
            row-gap: 12px;
            transition: border-color .2s;
            transition-behavior: normal;
            width: 100%;
        }

        .tab-box-contents, .tab-link {
            display: flex;
            flex-direction: column;
        }

        @media screen and (max-width: 991px) {
            .tabs-section {
                align-items: stretch;
                column-gap: 24px;
                flex-direction: column;
                margin-top: -24px;
                row-gap: 24px;
            }

            .tabs-menu {
                column-gap: 0;
                row-gap: 0;
            }
        }

        @media screen and (max-width: 767px) {
            .heading-four {
                font-size: 21px;
            }

            .tab-link {
                text-align: center;
            }

            .tab-text {
                display: none;
            }
        }

        @media screen and (max-width: 479px) {
            .small-text {
                font-size: 12px;
                line-height: 1.35em;
            }

            .heading-four {
                font-size: 18px;
            }

            .rounded-image, .widget {
                border-radius: 6px;
            }

            .widget-body {
                padding: 16px;
            }

            .widget-person {
                column-gap: 9px;
                row-gap: 9px;
            }

            .widget-chat-avatar {
                max-height: 32px;
            }

            .tabs-menu {
                flex-direction: column;
            }

            .tab-box {
                background-color: transparent;
                border-radius: 6px;
                min-height: auto;
                padding: 0;
            }

            .tab-link {
                padding: 12px;
            }

            .tab-box-contents {
                align-items: center;
                column-gap: 24px;
                flex-direction: column-reverse;
                row-gap: 24px;
            }
        }

        a:active, a:hover {
            outline: 0;
        }

        .w-tabs::after, .w-tabs::before {
            content: " ";
            display: table;
            grid-column-end: 2;
            grid-column-start: 1;
            grid-row-end: 2;
            grid-row-start: 1;
        }

        .w-tabs::after {
            clear: both;
        }

        .w-tab-link:focus {
            outline: 0;
        }

        .small-text.muted {
            opacity: .7;
        }

        .button.small {
            column-gap: 18px;
            padding: 8px 12px;
            row-gap: 18px;
        }

        .button.invert {
            background-color: #000;
            border-color: #222;
            filter: invert();
        }

        .rounded-image.tab-image {
            margin-bottom: -72px;
            max-width: 323px;
        }

        .widget.profile {
            width: 258px;
        }

        .widget.message {
            background-color: #fff;
            width: 379px;
        }

        .widget.notification {
            display: flex;
            min-height: 200px;
            text-align: center;
            width: 266px;
        }

        .widget-body.align-center {
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .tab-box.bg-pastel-1 {
            background-color: #ffe6e5;
        }

        .tab-box.bg-pastel-2 {
            background-color: #fff5d6;
        }

        .tab-link:hover {
            border-color: transparent;
        }

        .tab-link.w--current {
            background-color: #f4f3f1;
        }

        @media screen and (max-width: 991px) {
            .tab-box.bg-pastel-1 {
                height: auto;
            }
        }

        @media screen and (max-width: 767px) {
            .heading-four.tab-heading {
                font-size: 16px;
            }
        }

        @media screen and (max-width: 479px) {
            .small-text.muted {
                font-size: 13px;
            }

            .rounded-image.tab-image {
                margin-bottom: 0;
                max-width: 100%;
            }

            .tab-box.bg-pastel-1, .tab-box.bg-pastel-2 {
                background-color: transparent;
            }
        }

        .button.invert:hover {
            background-color: #222;
            border-color: #222;
            filter: invert(0%);
        }

        .widget.profile.tab-widget {
            margin-left: 120px;
        }

        .widget.message.tab-widget {
            margin-left: 72px;
        }

        .widget.notification.tab-widget {
            margin-left: 96px;
        }

        .tab-link.w--current:hover {
            border-color: transparent;
        }

        @media screen and (max-width: 479px) {
            .widget.profile.tab-widget {
                margin-left: 0;
            }

            .widget.message.tab-widget {
                margin-left: 0;
                width: 100%;
            }

            .widget.notification.tab-widget {
                margin-left: 0;
            }
        }
    }
</style>

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
                <p class="mw-pictures-clean">No tab items available.</p>
            @else
                @foreach ($tabs as $slide)
                    @php $count++; @endphp
                    <a class="tab-link w-inline-block w-tab-link w--current {{ $count == 1 ? 'active' : '' }}" href="javascript:;">
                        {!! isset($slide['icon']) ? $slide['icon'] . ' ' : '' !!}<span class="mb-0">{{ $slide['title'] ?? 'Tab title 1' }}</span>
                    </a>
                @endforeach
            @endif
        </div>

        <div class="tabs-content w-tab-content">
            <div class="widget-body background-color-element element">
                <div class="tab-box">
                    @php $count = 0; @endphp
                    @if($tabs->isEmpty())
                        <p class="mw-pictures-clean">No tab items available.</p>
                    @else
                         @foreach ($tabs as $key => $slide)
                        @php
                            $count++;
                            $edit_field_key = $slide['id'] ?? $key;
                        @endphp
                        <div class="tabs-content w-tab-content mw-ui-box-tab-content" style="{{ $count != 1 ? 'display: none;' : 'display: block;' }}">
                            <div class="tab-box-contents edit" field="tab-item-{{ $edit_field_key }}" rel="module-{{ $params['id'] }}">
                                <img loading="lazy" class="rounded-image tab-image" src="{{ template_url('resources/assets/img/layouts/gallery-1-6.jpg') }}" />
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
