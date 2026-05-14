@php
    /*
    type: layout
    name: Default
    description: Default language switcher template
    */
@endphp

<nav class="navbar module-multilanguage lang-dropdown">
    <ul class="navbar-nav">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center lang-flag-btn modern-lang-btn"
               href="#"
               id="dropdownMenuButton"
               role="button"
               data-bs-toggle="dropdown"
               aria-expanded="false"
            >
                @if($current_language['display_icon'])
                    <img src="{{ $current_language['display_icon'] }}" alt="{{ $current_language['display_name'] }}"
                         class="lang-flag">
                @else
                    <span
                        class="mw-flag-icon mw-flag-icon-{{ get_flag_icon($current_language['locale']) }} lang-flag"></span>
                @endif
            </a>

            <ul class="dropdown-menu dropdown-menu-end p-2 modern-lang-dropdown" aria-labelledby="dropdownMenuButton">
                @if(!empty($supported_languages))
                    @foreach($supported_languages as $language)
                        @if($language['is_active'])
                            <li>
                                <a class="dropdown-item d-flex align-items-center lang-flag-link modern-lang-link p-2 @if($language['locale'] == $current_language['locale']) active @endif"
                                   href="?localeRedirect={{ $language['locale'] }}"
                                   title="{{ $language['display_name'] ?: $language['language'] }}">
                                    @if($language['display_icon'])
                                        <img src="{{ $language['display_icon'] }}" alt="{{ $language['display_name'] }}"
                                             class="lang-flag">
                                    @else
                                        <span
                                            class="mw-flag-icon mw-flag-icon-{{ get_flag_icon($language['locale']) }} lang-flag"></span>
                                    @endif
                                    <span class="ms-2">{{ $language['display_name'] ?: $language['language'] }}</span>
                                    @if($language['locale'] == $current_language['locale'])
                                        <svg class="ms-auto" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20,6 9,17 4,12"></polyline>
                                        </svg>
                                    @endif
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </li>
    </ul>
</nav>

<style>
    .module-multilanguage {
        .dropdown-menu{
            border: none;
            background: var(--bs-light, #f8f9fa);
            min-width: 150px !important;
            width: 100%;
            max-width: none;
            position: absolute !important; /* Make dropdown absolute */
            left: 0 !important;
            top: 100% !important;
            z-index: 1050;

            ul > li > a:hover span:after, .mw-menu-skin-com ul > li.active span {
                content: unset !important
            }
        }
    }

    .modern-lang-btn {
        border-radius: 12px;
        padding: 6px 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: box-shadow 0.2s, transform 0.2s;
        min-width: 44px;
        min-height: 44px; /* WCAG 2.5.5 AI-556: 44px touch target */
        font-weight: 500;
        gap: 6px;
        border: none;
        width: 100%; /* button matches dropdown width */
        box-sizing: border-box;
    }
    .modern-lang-btn:hover, .modern-lang-btn:focus {
        box-shadow: 0 4px 16px rgba(0,0,0,0.10);
        transform: translateY(-1px) scale(1.03);
        border: none; /* ensure no border on hover/focus */
    }
    .modern-lang-btn .lang-flag {
        width: 22px;
        height: 16px;
        border-radius: 6px;
        object-fit: cover;
        margin-right: 2px;
    }
    .chevron-icon {
        vertical-align: middle;
        transition: transform 0.2s;
        opacity: 0.7;
    }
    .modern-lang-btn[aria-expanded="true"] .chevron-icon {
        transform: rotate(180deg);
        opacity: 1;
    }
    .modern-lang-dropdown {
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.10);
        padding: 8px 0;
        animation: fadeInLangDropdown 0.18s;
        border: none;
        min-width: 100% !important;
        width: 100%;
        max-width: none;
        box-sizing: border-box;
        position: static !important; /* Prevent double positioning */
    }
    @keyframes fadeInLangDropdown {
        from { opacity: 0; transform: translateY(-8px) scale(0.98);}
        to { opacity: 1; transform: translateY(0) scale(1);}
    }
    .modern-lang-link {
        border-radius: 8px;
        transition: background 0.18s, color 0.18s, transform 0.18s;
        font-size: 15px;
        font-weight: 500;
        gap: 8px;
        min-height: 44px; /* WCAG 2.5.5 AI-556: 44px touch target */
        display: flex;
        align-items: center;
        border: none; /* removed border */
    }
    .modern-lang-link .lang-flag {
        width: 20px;
        height: 14px;
        border-radius: 4px;
        object-fit: cover;
    }
    .modern-lang-link:hover, .modern-lang-link.active {
        background: var(--bs-light, #f8f9fa);
        color: var(--mw-primary-color, inherit);
        transform: scale(1.03);
        border: none; /* ensure no border on hover/active */
    }
    .modern-lang-link.active {
        font-weight: 600;
        border-left: 3px solid var(--mw-primary-color, #0d6efd);
    }
    @media (max-width: 576px) {
        .modern-lang-btn {
            padding: 4px 8px;
            font-size: 14px;
        }
        .modern-lang-dropdown {
            min-width: 100px !important;
        }
        .modern-lang-link {
            font-size: 14px;
            min-height: 44px; /* WCAG 2.5.5 AI-556: keep 44px on mobile */
        }
    }
</style>

<script>
    mw.lib.require('flag_icons')
    // Ensure Bootstrap dropdown works if not already initialized
    document.addEventListener('DOMContentLoaded', function () {
        var dropdownBtn = document.getElementById('dropdownMenuButton');
        if (dropdownBtn && typeof bootstrap !== 'undefined') {
            new bootstrap.Dropdown(dropdownBtn);
        }
    });
</script>
