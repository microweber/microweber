@include('modules.social_links::components.custom-css')

@php

/*

type: layout

name: Skin-1

description: Skin-1

*/

@endphp

<ul class="mw-socialLinks list-inline no-style mb-0">
    @if ($facebook_enabled)
        <li class="me-3">
            <a href="{{ $facebook_url }}" target="_blank">
                @svg('modules.social_links-facebook')
            </a>
        </li>
    @endif

    @if ($x_enabled)
        <li class="me-3">
            <a href="{{ $x_url }}" target="_blank">
                @svg('modules.social_links-x')
            </a>
        </li>
    @endif

    @if ($pinterest_enabled)
        <li class="me-3">
            <a href="{{ $pinterest_url }}" target="_blank">
                @svg('modules.social_links-pinterest')
            </a>
        </li>
    @endif

    @if ($youtube_enabled)
        <li class="me-3">
            <a href="{{ $youtube_url }}" target="_blank">
                @svg('modules.social_links-youtube')
            </a>
        </li>
    @endif

    @if ($instagram_enabled)
        <li class="me-3">
            <a href="{{ $instagram_url }}" target="_blank">
                @svg('modules.social_links-instagram')
            </a>
        </li>
    @endif

    @if ($linkedin_enabled)
        <li class="me-3">
            <a href="{{ $linkedin_url }}" target="_blank">
                @svg('modules.social_links-linkedin')
            </a>
        </li>
    @endif

    @if ($github_enabled)
        <li class="me-3">
            <a href="{{ $github_url }}" target="_blank">
                @svg('modules.social_links-github')
            </a>
        </li>
    @endif

    @if ($soundcloud_enabled)
        <li class="me-3">
            <a href="{{ $soundcloud_url }}" target="_blank">
                @svg('modules.social_links-soundcloud')
            </a>
        </li>
    @endif


    @if ($discord_enabled)
        <li class="mx-1">
            <a href="{{ $discord_url }}" target="_blank">
                @svg('modules.social_links-discord')
            </a>
        </li>
    @endif

    @if ($skype_enabled)
        <li class="mx-1">
            <a href="{{ $skype_url }}" target="_blank">
                @svg('modules.social_links-skype')
            </a>
        </li>
    @endif

    @if ($telegram_enabled)
        <li class="mx-1">
            <a href="{{ $telegram_url }}" target="_blank">
                @svg('modules.social_links-telegram')
            </a>
        </li>
    @endif
</ul>
