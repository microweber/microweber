<div class="logo-module">

    <a href="{{ is_live_edit() ? 'javascript:void(0)' : site_url() }}" class="logo-link"@if(is_live_edit()) title="Use the menu to navigate" aria-disabled="true"@endif>
        @php
            // Image and text are independent — a logo can have an image, text, or both.
            $hasLogoImage = isset($logoimage) && !empty($logoimage);
            $hasLogoText  = isset($text) && $text !== '';
        @endphp
        @if(!$hasLogoImage && !$hasLogoText)
            {{-- Nothing configured: show the "add logo" prompt only while editing.
                 On the public site render nothing (no empty placeholder). --}}
            @if(is_live_edit())
                <span class="logo-text logo-text--placeholder">
                    {!! lnotif('Click to add logo') !!}
                </span>
            @endif
        @else
            @if($hasLogoImage)

                <img src="{{ $logoimage }}" class="logo-image" alt="{{ $hasLogoText ? $text : (get_option('website_title', 'website') ?: 'Home') }}" style="max-width: {{ isset($size) ? $size . 'px' : '200px' }};"/>
            @endif
            @if($hasLogoText)
                {{-- Rendered alongside the image when both are set; omitted entirely when
                     the user did not enter any text. --}}
                <span class="logo-text {{ $hasLogoImage ? 'logo-text--with-image' : '' }}" style="color: {{ isset($text_color) ? $text_color : 'inherit' }}; font-family: {{ isset($font_family) ? $font_family : 'inherit' }}; font-size: {{ isset($font_size) ? $font_size : '30' }}px;">
                    {{ $text }}
                </span>
            @endif
        @endif
    </a>
</div>

<style>

    .logo-module {
        /*text-align: center;*/
        margin: 20px 0;
    }

    .logo-module {
        display: inline-block !important;
        min-width: 160px !important;
    }
    .logo-module .logo-link {
        display: inline-block !important;
        min-width: 160px !important;
        min-height: 60px !important;
        line-height: 1 !important;
        overflow: visible !important;
        padding: 0 !important;
    }
    .logo-module img {
        width: auto !important;
        height: 60px !important;
        max-width: none !important;
        display: inline-block !important;
        vertical-align: middle !important;
    }
    .logo-text {
        display: inline-block;
        vertical-align: middle;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    /* Gap between the image and the text when a logo has both. */
    .logo-module .logo-text--with-image {
        margin-left: 10px;
    }
    @media (max-width: 575px) {

        .logo-module {
            min-width: 0;
            overflow: hidden;
        }
        .logo-module .logo-link {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }
    }
</style>
