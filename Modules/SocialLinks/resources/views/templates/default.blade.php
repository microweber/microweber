<div class="mw-social-links">
    @if($facebook_enabled)
        <a href="{{ $facebook_url }}" target="_blank">
            @svg('modules.social_links-facebook')
        </a>
    @endif

    @if($x_enabled)
        <a href="{{ $x_url }}" target="_blank">
            @svg('modules.social_links-x')
        </a>
    @endif

    @if($pinterest_enabled)
        <a href="{{ $pinterest_url }}" target="_blank">
            @svg('modules.social_links-pinterest')
        </a>
    @endif

    @if($linkedin_enabled)
        <a href="{{ $linkedin_url }}" target="_blank">
            @svg('modules.social_links-linkedin')
        </a>
    @endif

    @if($viber_enabled)
        <a href="{{ $viber_url }}" target="_blank">
            @svg('modules.social_links-viber')
        </a>
    @endif

    @if($whatsapp_enabled)
        <a href="whatsapp://send?text=Check this out: {{ mw()->url->current() }}" target="_blank">
            @svg('modules.social_links-whatsapp')
        </a>
    @endif

    @if($telegram_enabled)
        <a href="{{ $telegram_url }}" target="_blank">
            @svg('modules.social_links-telegram')
        </a>
    @endif

    @if($youtube_enabled)
        <a href="{{ $youtube_url }}" target="_blank">
            @svg('modules.social_links-youtube')
        </a>
    @endif

    @if($instagram_enabled)
        <a href="{{ $instagram_url }}" target="_blank">
            @svg('modules.social_links-instagram')
        </a>
    @endif

    @if($github_enabled)
        <a href="{{ $github_url }}" target="_blank">
            @svg('modules.social_links-github')
        </a>
    @endif

    @if($soundcloud_enabled)
        <a href="{{ $soundcloud_url }}" target="_blank">
            @svg('modules.social_links-soundcloud')
        </a>
    @endif

    @if($discord_enabled)
        <a href="{{ $discord_url }}" target="_blank">
            @svg('modules.social_links-discord')
        </a>
    @endif

    @if($skype_enabled)
        <a href="{{ $skype_url }}" target="_blank">
            @svg('modules.social_links-skype')
        </a>
    @endif

    @if(!$facebook_enabled && !$x_enabled && !$pinterest_enabled && !$linkedin_enabled && !$viber_enabled && !$whatsapp_enabled && !$telegram_enabled && !$youtube_enabled && !$instagram_enabled && !$github_enabled && !$soundcloud_enabled && !$discord_enabled && !$skype_enabled)
        <p>No sharing options enabled. Please enable at least one sharing option in the settings.</p>
    @endif
</div>
