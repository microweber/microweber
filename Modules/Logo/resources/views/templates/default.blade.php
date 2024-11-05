<div class="logo-module">
    <a href="{{ site_url() }}" class="logo-link">
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
        text-align: center;
        margin: 20px 0;
    }
    .logo-text {
        display: inline-block;
        margin-top: 10px;
    }
</style>
