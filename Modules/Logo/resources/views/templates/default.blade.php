<div class="logo-module">
    <a
        @if(is_live_edit())
            href="{{ site_url() }}"
        @else

            href="{{ site_url() }}"
        @endif


        class="logo-link">
        @if(isset($logoimage) && !empty($logoimage))
            <img src="{{ $logoimage }}" alt="{{ isset($text) ? $text : '' }}" style="max-width: {{ isset($size) ? $size . 'px' : '200px' }};"/>
        @elseif(isset($text) && !empty($text))
            <span class="logo-text" style="color: {{ isset($text_color) ? $text_color : 'inherit' }}; font-family: {{ isset($font_family) ? $font_family : 'inherit' }}; font-size: {{ isset($font_size) ? $font_size : '30' }}px;">
                {{ $text }}
            </span>
        @else
            <span class="logo-text">
               {!! lnotif('Click to add logo') !!}
            </span>
        @endif
    </a>
</div>

<style>
    .logo-module {
        /*text-align: center;*/
        margin: 20px 0;
        /*
         * On narrow viewports (≤390px) the logo shares its header
         * row with the hamburger icon. Without `min-width: 0` the
         * logo's intrinsic text width forces the column to stay at
         * full size and pushes the hamburger off-screen. Setting
         * min-width:0 + overflow:hidden lets the column shrink, and
         * the inner ellipsis on .logo-link / .logo-text gracefully
         * truncates very long brand names instead of breaking the
         * layout.
         */
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
    .logo-module img {
        max-width: 100%;
        height: auto;
    }
    .logo-text {
        display: inline-block;
        margin-top: 10px;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
