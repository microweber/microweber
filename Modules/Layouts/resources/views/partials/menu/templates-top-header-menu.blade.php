@php
    $default_contact_us_link = $default_contact_us_link ?? 'yes';
    $default_header_socials = $default_header_socials ?? 'no';
    $default_phone_text =  $default_phone_text ?? 'Phone';
    $default_phone_text_value =  $default_phone_text_value ?? '123 456 789';
    $default_profile_link = $default_profile_link ?? 'no';
    $default_search_bar = $default_search_bar ?? 'no';
    $default_shopping_cart = $default_shopping_cart ?? 'no';
    $default_multilanguage =  $default_multilanguage ?? 'no';
    $default_is_sticky_nav = $default_is_sticky_nav ?? 'no';

    /* Contact Us */
    $contact_us_link = get_option('contact_us_link', $params['id']);
    if ($contact_us_link == '') {
        $contact_us_link = $default_contact_us_link;
    }

    /* Header Socials */
    $header_socials = get_option('header_socials', $params['id']);
    if ($header_socials == '') {
        $header_socials = $default_header_socials;
    }

    /* Phone */
    $phone_text = get_option('phone_text', $params['id']);
    if ($phone_text == '') {
        $phone_text = $default_phone_text;
    }

    $phone_text_value = get_option('phone_text_value', $params['id']);
    if ($phone_text_value == '') {
        $phone_text_value = $default_phone_text_value;
    }

    /* Profile Link */
    $profile_link = get_option('profile_link', $params['id']);
    if ($profile_link == '') {
        $profile_link = $default_profile_link;
    }

    /* Search */
    $search_bar = get_option('search_bar', $params['id']);
    if ($search_bar == '') {
        $search_bar = $default_search_bar;
    }

    /* Shopping Cart */
    $shopping_cart = get_option('shopping_cart', $params['id']);
    if ($shopping_cart == '') {
        $shopping_cart = $default_shopping_cart;
    }

    /* Multilanguage */
    $multilanguage = get_option('multilanguage', $params['id']);
    if ($multilanguage == '') {
        $multilanguage = $default_multilanguage;
    }

    /* Sticky Navigation */
    $is_sticky_nav = get_option('is_sticky_nav', $params['id']);
    if ($is_sticky_nav == '') {
        $is_sticky_nav = $default_is_sticky_nav;
    }

@endphp


<style>
    .top-header-background.header-top ul {
        list-style: none;
    }
</style>

<div class="top-header-background header-top navbar">
    <div>
        @include('modules.layouts::partials.menu.parts.socials')
    </div>

    <ul class="d-flex align-items-center flex-wrap gap-2 mb-0">
        @include('modules.layouts::partials.menu.parts.multilanguage')
        @include('modules.layouts::partials.menu.parts.phone')
        @include('modules.layouts::partials.menu.parts.contact')
        @include('modules.layouts::partials.menu.parts.search_bar')
        @include('modules.layouts::partials.menu.parts.profile_link')
        @include('modules.layouts::partials.menu.parts.shopping_cart')
    </ul>
</div>
