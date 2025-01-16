@php
    /*

    type: layout

    name: Skin-7

    description: Skin-7

    */
@endphp

<style>
    .social-links-rounded {

        ul {
            margin: 0;
            padding: 0;
        }

        li {
            list-style: none;
            display: inline-block;
            vertical-align: top;
        }

        a {
            border: 1px solid #ececec;
            border-radius: 100px;
            display: inline-block;
            vertical-align: top;
            margin: 2px 2px 5px 2px;
            width: 50px;
            height: 50px;
            line-height: 40px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;

            i::before {
                font-size: 20px;
                color: var(--mw-primary-color);

            }
        }

        a:hover {
            background-color: var(--mw-primary-color);
            border-color: transparent;
            i::before {

                color: #fff;
            }
        }
    }
</style>
<div class="social-links-rounded">
    <ul class="list-inline no-style">

        @if ($facebook_enabled)
            <li class="me-3"><a href="{{ $facebook_url }}" target="_blank">@svg('modules.social_links-facebook')</a></li>
        @endif

        @if ($x_enabled)
            <li class="me-3"><a href="{{ $x_url }}" target="_blank">@svg('modules.social_links-x')</a></li>
        @endif

        @if ($pinterest_enabled)
            <li class="me-3"><a href="{{ $pinterest_url }}" target="_blank">@svg('modules.social_links-pinterest')</a></li>
        @endif

        @if ($youtube_enabled)
            <li class="me-3"><a href="{{ $youtube_url }}" target="_blank">@svg('modules.social_links-youtube')</a></li>
        @endif

        @if ($instagram_enabled)
            <li class="me-3"><a href="{{ $instagram_url }}" target="_blank">@svg('modules.social_links-instagram')</a></li>
        @endif

        @if ($linkedin_enabled)
            <li class="me-3"><a href="{{ $linkedin_url }}" target="_blank">@svg('modules.social_links-linkedin')</a></li>
        @endif

        @if ($github_enabled)
            <li class="me-3"><a href="{{ $github_url }}" target="_blank">@svg('modules.social_links-github')</a></li>
        @endif

        @if ($soundcloud_enabled)
            <li class="me-3"><a href="{{ $soundcloud_url }}" target="_blank">@svg('modules.social_links-soundcloud')</a></li>
        @endif

        @if ($discord_enabled)
            <li class="mx-1"><a href="{{ $discord_url }}" target="_blank">@svg('modules.social_links-discord')</a></li>
        @endif

        @if ($skype_enabled)
            <li class="mx-1"><a href="{{ $skype_url }}" target="_blank">@svg('modules.social_links-skype')</a></li>
        @endif

        @if ($telegram_enabled)
            <li class="mx-1"><a href="{{ $telegram_url }}" target="_blank">@svg('modules.social_links-telegram')</a></li>
        @endif
    </ul>
</div>
