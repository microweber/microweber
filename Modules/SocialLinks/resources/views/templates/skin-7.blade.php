@php
    /*

    type: layout

    name: Skin-7

    description: Skin-7

    */
@endphp

<ul class="list-inline no-style">

    @if ($facebook_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $facebook_url }}" target="_blank">
                @svg('modules.social_links-facebook')
            </a>
            <span class="ms-2">Facebook</span>
        </li>
    @endif

    @if ($x_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $x_url }}" target="_blank">
                @svg('modules.social_links-x')
            </a>
            <span class="ms-2">x</span>
        </li>
    @endif

    @if ($pinterest_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $pinterest_url }}" target="_blank">
                @svg('modules.social_links-pinterest')
            </a>
            <span class="ms-2">Pinterest</span>
        </li>
    @endif

    @if ($youtube_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $youtube_url }}" target="_blank">
                @svg('modules.social_links-youtube')
            </a>
            <span class="ms-2">Youtube</span>
        </li>
    @endif

    @if ($instagram_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $instagram_url }}" target="_blank">
                @svg('modules.social_links-instagram')
            </a>
            <span class="ms-2">Instagram</span>
        </li>
    @endif

    @if ($linkedin_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $linkedin_url }}" target="_blank">
                @svg('modules.social_links-linkedin')
            </a>
            <span class="ms-2">LinkedIn</span>
        </li>
    @endif

    @if ($github_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $github_url }}" target="_blank">
                @svg('modules.social_links-github')
            </a>
            <span class="ms-2">GitHub</span>
        </li>
    @endif

    @if ($soundcloud_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $soundcloud_url }}" target="_blank">
                @svg('modules.social_links-soundcloud')
            </a>
            <span class="ms-2">Soundcloud</span>
        </li>
    @endif

    @if ($discord_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $discord_url }}" target="_blank">
                @svg('modules.social_links-discord')
            </a>
            <span class="ms-2">Discord</span>
        </li>
    @endif

    @if ($skype_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $skype_url }}" target="_blank">
                @svg('modules.social_links-skype')
            </a>
            <span class="ms-2">Skype</span>

        </li>
    @endif

    @if ($telegram_enabled)
        <li class="py-0 d-flex align-items-center">
            <a href="{{ $telegram_url }}" target="_blank">
                @svg('modules.social_links-telegram')
            </a>
            <span class="ms-2">Telegram</span>

        </li>
    @endif
</ul>
