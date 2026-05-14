@include('modules.social_links::components.custom-css')

{{-- AI-295: gate every social anchor on BOTH `_enabled` AND a non-empty
     URL. `SocialLinksModule::getSocialNetworksData()` auto-enables
     facebook/x/instagram/linkedin with empty `_url` strings on fresh
     installs (lines 75-83); without this gate the blade rendered
     empty-href anchors — exactly the "empty anchor" pattern the audit
     flagged. WhatsApp is the only exception: its href is a runtime-built
     `whatsapp://send?text=…` URL, never stored as a setting, so the
     enable flag alone gates it. --}}

<div class="mw-socialLinks">
    @if($facebook_enabled && !empty($facebook_url))
        <a href="{{ $facebook_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-facebook')
        </a>
    @endif

    @if($x_enabled && !empty($x_url))
        <a href="{{ $x_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-x')
        </a>
    @endif

    @if($pinterest_enabled && !empty($pinterest_url))
        <a href="{{ $pinterest_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-pinterest')
        </a>
    @endif

    @if($linkedin_enabled && !empty($linkedin_url))
        <a href="{{ $linkedin_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-linkedin')
        </a>
    @endif

    @if($viber_enabled && !empty($viber_url))
        <a href="{{ $viber_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-viber')
        </a>
    @endif

    @if($whatsapp_enabled)
        <a href="whatsapp://send?text=Check this out: {{ mw()->url->current() }}" target="_blank">
            @svg('modules.social_links-whatsapp')
        </a>
    @endif

    @if($telegram_enabled && !empty($telegram_url))
        <a href="{{ $telegram_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-telegram')
        </a>
    @endif

    @if($youtube_enabled && !empty($youtube_url))
        <a href="{{ $youtube_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-youtube')
        </a>
    @endif

    @if($instagram_enabled && !empty($instagram_url))
        <a href="{{ $instagram_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-instagram')
        </a>
    @endif

    @if($github_enabled && !empty($github_url))
        <a href="{{ $github_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-github')
        </a>
    @endif

    @if($soundcloud_enabled && !empty($soundcloud_url))
        <a href="{{ $soundcloud_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-soundcloud')
        </a>
    @endif

    @if($discord_enabled && !empty($discord_url))
        <a href="{{ $discord_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-discord')
        </a>
    @endif

    @if($skype_enabled && !empty($skype_url))
        <a href="{{ $skype_url }}" target="_blank" rel="noopener noreferrer">
            @svg('modules.social_links-skype')
        </a>
    @endif

    @if(!$facebook_enabled && !$x_enabled && !$pinterest_enabled && !$linkedin_enabled && !$viber_enabled && !$whatsapp_enabled && !$telegram_enabled && !$youtube_enabled && !$instagram_enabled && !$github_enabled && !$soundcloud_enabled && !$discord_enabled && !$skype_enabled)
        <p class="mw-pictures-clean">No sharing options enabled. Please enable at least one sharing option in the settings.</p>
    @endif
</div>
