@php
    $moduleId = $moduleId ?? 'init_CookieNoticeDefault';
@endphp

<div class="cookie-notice-wrapper"

     x-load="visible"
     x-load-src="{{ asset('modules/cookie_notice/js/cookie-notice-alpine.js') }}"

     x-data="cookieNotice('{{ $moduleId }}')">
    <div class="cookie-notice-panel"
         :class="{'hidden': !showPanel}"
         :style="{
            backgroundColor: '{{ $settings['backgroundColor'] ?? '#ffffff' }}',
            color: '{{ $settings['textColor'] ?? '#000000' }}'
         }">
        <div class="cookie-notice-content">
            <div class="cookie-notice-header">
                <h3>{{ $settings['cookieNoticeTitle'] }}</h3>
                <button type="button" x-on:click="hide" class="close-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="cookie-notice-body">
                <p :style="{
            backgroundColor: '{{ $settings['backgroundColor'] ?? '#ffffff' }}',
            color: '{{ $settings['textColor'] ?? '#000000' }}'
         }">{{ $settings['cookieNoticeText'] }}</p>


            </div>

            <div class="cookie-notice-footer">

                <a href="{{ $settings['cookiePolicyURL'] ?? 'privacy-policy' }}" class="cookie-policy-link">
                    {{ _e('Learn more') }}
                </a>


                <button type="button" x-on:click="acceptAll" class="accept-all-button">
                    {{ _e('Accept All') }}
                </button>

            </div>
        </div>
    </div>


</div>
