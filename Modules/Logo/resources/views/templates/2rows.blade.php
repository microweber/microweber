<?php

/*

  type: layout

  name: 2 Rows

  description: Logo with text below

*/

?>

<a href="{{ site_url() }}" class="module-logo module-logo-2rows" style="width: auto;">
    @if ($logoimage == '' && $text == '')
        @if (is_live_edit())
            <span class="mw-logo-no-values">{{ __('Click to add logo') }}</span>
        @endif
    @else
        @if ($logoimage)
            <span class="module-logo-row-top" style="width: {{ $size }};">
                <img src="{{ $logoimage }}" alt="" style="max-width: 100%; width: {{ $size }};"/>
            </span>
        @endif
        @if ($text)
            <span class="module-logo-row-bottom">
                <span class="module-logo-text"
                      style="color: {{ $text_color }}; font-family: {{ $font_family }}; font-size: {{ $font_size }}px;">
                    {{ $text }}
                </span>
            </span>
        @endif
    @endif
</a>
