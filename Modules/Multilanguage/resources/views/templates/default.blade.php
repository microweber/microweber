@php
/*
type: layout
name: Default
description: Default language switcher template
*/
@endphp

<div class="module-multilanguage">
    <div class="language-selector">
        <div class="current-language d-flex align-items-center">
            @if($current_language['display_icon'])
                <img src="{{ $current_language['display_icon'] }}" alt="{{ $current_language['display_name'] }}" class="me-2" style="max-width: 20px; max-height: 20px;">
            @else
                <span class="flag-icon flag-icon-{{ get_flag_icon($current_language['locale']) }} me-2"></span>
            @endif
            
            <span>{{ $current_language['display_name'] ?: $current_language['language'] }}</span>
        </div>
        
        @if(!empty($supported_languages))
            <div class="languages-list">
                <ul class="list-unstyled mt-2">
                    @foreach($supported_languages as $language)
                        @if($language['locale'] != $current_language['locale'] && $language['is_active'] == 'y')
                            <li class="py-1">
                                <a href="?lang={{ $language['locale'] }}" class="d-flex align-items-center">
                                    @if($language['display_icon'])
                                        <img src="{{ $language['display_icon'] }}" alt="{{ $language['display_name'] }}" class="me-2" style="max-width: 20px; max-height: 20px;">
                                    @else
                                        <span class="flag-icon flag-icon-{{ get_flag_icon($language['locale']) }} me-2"></span>
                                    @endif
                                    
                                    <span>{{ $language['display_name'] ?: $language['language'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
