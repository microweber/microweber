@php
/*
type: layout
name: Bootstrap
description: Bootstrap language switcher template
*/
@endphp

<div class="module-multilanguage">
    <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="languageSwitcher" data-bs-toggle="dropdown" aria-expanded="false">
            @if($current_language['display_icon'])
                <img src="{{ $current_language['display_icon'] }}" alt="{{ $current_language['display_name'] }}" style="max-width: 20px; max-height: 20px;">
            @else
                <span class="flag-icon flag-icon-{{ get_flag_icon($current_language['locale']) }}"></span>
            @endif
            
            <span class="ms-2">{{ $current_language['display_name'] ?: $current_language['language'] }}</span>
        </button>
        
        @if(!empty($supported_languages))
            <ul class="dropdown-menu" aria-labelledby="languageSwitcher">
                @foreach($supported_languages as $language)
                    @if($language['is_active'] == 'y')
                        <li>
                            <a class="dropdown-item d-flex align-items-center @if($language['locale'] == $current_language['locale']) active @endif" href="?lang={{ $language['locale'] }}">
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
        @endif
    </div>
</div>
