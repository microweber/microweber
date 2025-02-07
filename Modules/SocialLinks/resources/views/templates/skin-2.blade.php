@include('modules.social_links::components.custom-css')


@php
    /*

    type: layout

    name: Skin-2

    description: Skin-2

    */
@endphp

<ul class="mw-socialLinks list-inline no-style mb-0">

    @if ($facebook_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $facebook_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-facebook')
                </a>
            </div>
        </li>
    @endif

    @if ($x_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $x_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-x')
                </a>
            </div>
        </li>
    @endif

    @if ($pinterest_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $pinterest_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-pinterest')
                </a>
            </div>
        </li>
    @endif

    @if ($youtube_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $youtube_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-youtube')
                </a>
            </div>
        </li>
    @endif

    @if ($instagram_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $instagram_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-instagram')
                </a>
            </div>
        </li>
    @endif

    @if ($linkedin_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $linkedin_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-linkedin')
                </a>
            </div>
        </li>
    @endif

    @if ($github_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $github_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-github')
                </a>
            </div>
        </li>
    @endif

    @if ($soundcloud_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $soundcloud_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-soundcloud')
                </a>
            </div>
        </li>
    @endif


    @if ($discord_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $discord_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-discord')
                </a>
            </div>
        </li>
    @endif

    @if ($skype_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $skype_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-skype')
                </a>
            </div>
        </li>
    @endif

    @if ($telegram_enabled)
        <li class="me-2 my-2">
            <div class="bg-body rounded-circle square w-60px d-flex align-items-center justify-content-center">
                <a href="{{ $telegram_url }}" target="_blank" class="text-opacity-5 position-absolute">
                    @svg('modules.social_links-telegram')
                </a>
            </div>
        </li>
    @endif
</ul>
