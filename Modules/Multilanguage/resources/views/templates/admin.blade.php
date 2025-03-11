@php
/*
type: layout
name: Admin
description: Admin language switcher template
*/
@endphp

<div class="dropdown ms-2">
    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        @if($current_language['display_icon'])
            <img src="{{ $current_language['display_icon'] }}" alt="{{ $current_language['display_name'] }}" style="max-width: 20px; max-height: 20px;">
        @else
            <span class="flag-icon flag-icon-{{ get_flag_icon($current_language['locale']) }}"></span>
        @endif
        
        <span class="d-none d-md-inline-block ms-1">{{ $current_language['display_name'] ?: $current_language['language'] }}</span>
    </button>
    
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
        @if(!empty($supported_languages))
            @foreach($supported_languages as $language)
                @if($language['is_active'] == 'y')
                    <a class="dropdown-item d-flex align-items-center @if($language['locale'] == $current_language['locale']) active @endif" href="?lang={{ $language['locale'] }}">
                        @if($language['display_icon'])
                            <img src="{{ $language['display_icon'] }}" alt="{{ $language['display_name'] }}" class="me-2" style="max-width: 20px; max-height: 20px;">
                        @else
                            <span class="flag-icon flag-icon-{{ get_flag_icon($language['locale']) }} me-2"></span>
                        @endif
                        
                        <span>{{ $language['display_name'] ?: $language['language'] }}</span>
                    </a>
                @endif
            @endforeach
        @endif
        
        @if(isset($show_settings_link) && $show_settings_link)
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="javascript:mw_liveEdit_language_settings_open_modal()">
                <i class="mdi mdi-cog-outline me-1"></i> {{ _e('Settings') }}
            </a>
        @endif
    </div>
</div>

@if(isset($show_settings_link) && $show_settings_link)
    <script>
        function mw_liveEdit_language_settings_open_modal() {
            var data = {};
            data.template = 'mw_default';
            mw.tools.open_module_modal('multilanguage/language_settings', data, {
                overlay: true,
                skin: 'simple',
                title: '{{ _e('Language settings') }}'
            });
        }
    </script>
@endif
