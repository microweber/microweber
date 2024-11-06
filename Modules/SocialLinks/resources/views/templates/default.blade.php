<div class="mw-social-links">
    @if($facebook_enabled)
        <a href="{{ $facebook_url }}" target="_blank">
            @svg('modules.social_links-facebook')
        </a>
    @endif

    @if($twitter_enabled)
        <a href="{{ $twitter_url }}" target="_blank">
            @svg('modules.social_links-twitter')

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
        <a href="whatsapp://send?text=Check this out: {{ mw()->url->current() }}" target="_blank">
            @svg('modules.social_links-telegram')

        </a>
    @endif

    @if(!$facebook_enabled && !$twitter_enabled && !$pinterest_enabled && !$linkedin_enabled && !$viber_enabled && !$whatsapp_enabled)
        <p>No sharing options enabled. Please enable at least one sharing option in the settings.</p>
    @endif
</div>
