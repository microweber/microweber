@php
/*
type: layout
name: Minimal
description: Minimal language switcher template
*/
@endphp

<div class="module-multilanguage-minimal">
    @if(!empty($supported_languages))
        <div class="language-links d-flex">
            @foreach($supported_languages as $language)
                @if($language['is_active'] == 'y')
                    <a href="?lang={{ $language['locale'] }}" class="language-link mx-1 @if($language['locale'] == $current_language['locale']) active @endif">
                        @if($language['display_icon'])
                            <img src="{{ $language['display_icon'] }}" alt="{{ $language['display_name'] }}" style="max-width: 20px; max-height: 20px;">
                        @else
                            <span class="flag-icon flag-icon-{{ get_flag_icon($language['locale']) }}"></span>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    @endif
</div>
