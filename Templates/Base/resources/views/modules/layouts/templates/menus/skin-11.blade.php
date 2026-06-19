{{--
  type: layout
  content_type: static
  name: Menu - LinkTree
  position: 11
  description: Menu - LinkTree
  categories: Menu
--}}

@php
    $header_top_menu = get_option('header_top_menu', $params['id']);
@endphp

@if(isset($header_top_menu) && $header_top_menu)
    <div class="templates-top-header-menu">
        @include('modules.layouts::partials.menu.parts.templates-top-header-menu')
    </div>
@endif

<style>
.linktree-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    min-height: 100vh;
}

.linktree-logo {
    margin-bottom: 20px;
    max-width: 250px;
}

/* Style the logo module for LinkTree design */
.linktree-logo .logo-module {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

.linktree-logo .logo-module a {
    display: block;
    padding: 8px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.linktree-logo .logo-module a:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
    transform: scale(1.05);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
}

.linktree-logo .logo-module img {
    max-width: 150px !important;
    max-height: 150px;
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    transition: all 0.3s ease;
    filter: brightness(1.1) contrast(1.1);
}

.linktree-logo .logo-module a:hover img {
    transform: scale(1.02);
    filter: brightness(1.2) contrast(1.2);
}

/* Simple floating animation */
.linktree-logo .logo-module {
    animation: simpleFloat 4s ease-in-out infinite;
}

@keyframes simpleFloat {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

/* Profile section styling */
.linktree-profile {
    margin-bottom: 40px;
    max-width: 400px;
    text-align: center;
}

.linktree-name {
    font-size: 28px;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 15px 0;
    text-shadow: 0 3px 15px rgba(0, 0, 0, 0.8), 0 1px 3px rgba(0, 0, 0, 0.9);
    letter-spacing: -0.5px;
    line-height: 1.2;
}

.linktree-description {
    font-size: 16px;
    font-weight: 400;
    color: #ffffff;
    margin: 0;
    line-height: 1.5;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7), 0 1px 3px rgba(0, 0, 0, 0.8);
    max-width: 350px;
    margin-left: auto;
    margin-right: auto;
}

/* Add subtle animation to profile text */
.linktree-profile {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.linktree-menu {
    width: 100%;
    max-width: 400px;
    margin-bottom: 30px;
}

.linktree-menu .linktree-nav {
    display: flex;
    flex-direction: column;
    width: 100%;
    list-style: none;
    padding: 0;
    margin: 0;
}

.linktree-menu .linktree-item {
    width: 100%;
    margin-bottom: 15px;
}

.linktree-menu .linktree-link {
    display: block;
    width: 100%;
    padding: 15px 20px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    color: #ffffff !important;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    text-align: center;
}

.linktree-menu .linktree-link:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    color: #ffffff !important;
}

.linktree-menu .linktree-link.active {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.5);
}

/* Target the actual menu classes */
.linktree-menu .menu_element_link,
.linktree-menu .nav-link {
    display: block;
    width: 100%;
    padding: 15px 20px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    color: #ffffff !important;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 15px;
}

.linktree-menu .menu_element_link::before,
.linktree-menu .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.linktree-menu .menu_element_link:hover::before,
.linktree-menu .nav-link:hover::before {
    left: 100%;
}

.linktree-menu .menu_element_link:hover,
.linktree-menu .nav-link:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    color: #ffffff !important;
}

.linktree-menu .menu_element_link.active,
.linktree-menu .nav-link.active {
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.25), rgba(255, 255, 255, 0.15));
    border-color: rgba(255, 255, 255, 0.6);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    transform: scale(1.05);
}

.linktree-menu .menu_element_link:active,
.linktree-menu .nav-link:active {
    transform: translateY(-1px) scale(0.98);
    transition: all 0.1s ease;
}

/* Pulse animation for active links */
.linktree-menu .menu_element_link.active::after,
.linktree-menu .nav-link.active::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 52px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.7;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Ensure list items are properly styled */
.linktree-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    width: 100%;
}

.linktree-menu li {
    width: 100%;
    margin-bottom: 0;
}

.linktree-social {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
}

.linktree-social a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    color: #ffffff;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.linktree-social a:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .linktree-container {
        padding: 20px 15px;
    }

    .linktree-menu {
        max-width: 100%;
    }

    .linktree-menu .linktree-link {
        padding: 12px 16px;
        font-size: 14px;
    }

    /* Mobile responsive for logo */
    .linktree-logo {
        max-width: 180px;
        margin-bottom: 10px;
    }

    .linktree-logo .logo-module img {
        max-width: 120px !important;
        max-height: 120px;
        width: 120px;
        height: 120px;
    }

    .linktree-logo .logo-module a {
    }

    /* Mobile responsive for profile section */
    .linktree-profile {
        margin-bottom: 30px;
        max-width: 100%;
        padding: 0 10px;
    }

    .linktree-name {
        font-size: 24px;
        margin-bottom: 12px;
        letter-spacing: -0.3px;
    }

    .linktree-description {
        font-size: 14px;
        line-height: 1.4;
        max-width: 280px;
    }
}
</style>

<section class="header-background mw-layout-dark-background mw-menu-skin-com d-flex align-items-center pb-0" id="mw-header-background">

    <module type="background" data-background-color="#0000008c" data-background-image="{{ asset('templates/big/img/layouts/linktree.jpg') }}" id="background-layout--{{ $params['id'] }}" />

    <div class="linktree-container mw-layout-container no-element edit w-100" field="layout-manu-linktree-{{ $params['id'] }}" rel="module">

        <div class="linktree-logo">
            <module type="logo" id="header-logo-{{ $params['id'] }}" class="module module-logo"/>
        </div>

        <div class="linktree-profile">
            <h1 class="linktree-name edit" field="linktree-name-{{ $params['id'] }}" rel="content">Your Name</h1>
            <p class="linktree-description edit" field="linktree-description-{{ $params['id'] }}" rel="content">Your short description goes here. Tell people who you are and what you do.</p>
        </div>

        <div class="linktree-menu">
            <module type="menu" name="header_menu" id="header_menu-{{ $params['id'] }}12" template="linktree"/>
        </div>

        <div class="linktree-social">
            <module type="social_links"/>
        </div>

    </div>

</section>
