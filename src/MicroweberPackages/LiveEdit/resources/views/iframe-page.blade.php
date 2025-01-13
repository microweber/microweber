<div class="mw-admin-live-edit-page">



    <div
        x-data="{}"
        x-init="() => {
            window.addEventListener('openAddContentAction', () => {
                 $wire.mountAction('addContentAction', {})
            });

            window.addEventListener('openModuleSettingsAction', (e) => {

                 $wire.mountAction('openModuleSettingsAction', {data:e.detail})
            });
        }"
    >

    </div>






    <div wire:ignore>


        <div>

            <script>

                // mw.lib.require('nouislider');
                // mw.require('components.css')
                //
                //

                // mw.lib.require('flag_icons');
                /*mw.iconLoader()

                    .addIconSet('iconsMindLine')
                    .addIconSet('iconsMindSolid')
                    .addIconSet('fontAwesome')
                    .addIconSet('materialDesignIcons')*/

            </script>

            <?php

            $bodyDarkClass = '';

            if(isset($_COOKIE['admin_theme_dark'])){
                $bodyDarkClass = 'theme-dark';
            }
            ?>


            <?php event_trigger('mw.live_edit.header'); ?>
        </div>

        <script>
            mw.quickSettings = {};
            mw.layoutQuickSettings = [];

            window.addEventListener('load', function () {
                if (mw.top() && mw.top().app && mw.top().app.liveEdit && mw.top().app.fontManager) {
                    mw.top().app.fontManager.addFonts({!! json_encode(\MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts()) !!});
                }

                const scrollContainer = document.querySelector("#live-edit-frame-holder");
                const frame = scrollContainer.querySelector("iframe");

                scrollContainer.addEventListener("wheel", (e) => {
                    if (e.target === scrollContainer) {
                        e.preventDefault();
                        const win = mw.top().app.canvas.getWindow();
                        win.scrollTo(0, (win.scrollY + e.deltaY) + (e.deltaY < 0 ? -10 : 10));
                    }

                });
            });

            <?php


            /*

             @php
                     $templateColors = [];
                     $getTemplateConfig = app()->template_manager->get_config();
                     if($getTemplateConfig){
                     $templateColors = get_template_colors_settings();
                     }
                     if(empty($templateColors)){
                     $templateColors =[['value' => '#000000']];
                     }

             @endphp
             @if(!empty($templateColors))
                 mw.tools.colorPickerColors = mw.tools.colorPickerColors || [];
                 mw.tools.colorPickerColors = [
                     @foreach($templateColors as $color)
                     '{{ $color['value'] }}',
                     @endforeach
                 ];
             @endif

             * */


            ?>
        </script>

        <div id="live-edit-app">
            Loading...
        </div>



<style>
    .fi-sidebar.active{
        transform: translateX(0%);
    }
    .fi-sidebar{
        transform: translateX(-100%);
        transition: var(--toolbar-height-animation-speed)
    }

    html  #toolbar{
        left: 0;

    }
    html.mw-live-edit-sidebar-start #toolbar{
        left: 250px;
        width: calc(100% - 250px);
        transition: var(--toolbar-height-animation-speed)
    }
</style>

<script>

    addEventListener('DOMContentLoaded', () => {
        document.getElementById('mw-live-edit-toolbar-back-to-admin-link').addEventListener('click', e => {
            e.preventDefault();
            document.querySelector('.fi-sidebar').classList.toggle('active')
            document.documentElement.classList.toggle('mw-live-edit-sidebar-start')
        })
    })

</script>



<aside style="position: absolute;z-index: 102;" x-data="{}" x-bind:class="
                $store.sidebar.isOpen
                    ? 'fi-sidebar-open w-[--sidebar-width] translate-x-0 shadow-xl ring-1 ring-gray-950\/5 dark:ring-white\/10 rtl:-translate-x-0 lg:sticky'                    : 'w-[--sidebar-width] -translate-x-full rtl:translate-x-full lg:sticky'
            " class="fi-sidebar fixed inset-y-0 start-0 z-30 flex flex-col h-screen content-start bg-white transition-all dark:bg-gray-900 lg:z-0 lg:bg-transparent lg:shadow-none lg:ring-0 lg:transition-none dark:lg:bg-transparent lg:translate-x-0 rtl:lg:-translate-x-0 fi-main-sidebar w-[--sidebar-width] -translate-x-full rtl:translate-x-full lg:sticky">
    <div class="overflow-x-clip">
        <header class="fi-sidebar-header flex h-16 items-center bg-white px-6 ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 lg:shadow-sm">
            <div>
                                    <a href="http://127.0.0.2/public/admin">
                        <img alt="Laravel logo" src="http://127.0.0.2/public/vendor/microweber-packages/frontend-assets-libs/img/logo.svg" style="height: 34px;" class="fi-logo flex">



                    </a>
                            </div>


                    </header>
    </div>

    <nav class="fi-sidebar-nav flex-grow flex flex-col gap-y-7 overflow-y-auto overflow-x-hidden px-6 py-8" style="scrollbar-gutter: stable">



        <ul class="fi-sidebar-nav-groups -mx-2 flex flex-col gap-y-7">
                            <li x-data="{ label: '' }" data-group-label="" class="fi-sidebar-group flex flex-col gap-y-1 fi-active">


    <ul class="fi-sidebar-group-items flex flex-col gap-y-1">

            <li class="fi-sidebar-item fi-active fi-sidebar-item-active flex flex-col gap-y-1">
    <a href="http://127.0.0.2/public/admin" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 bg-gray-100 dark:bg-white/5">
                    <!--[if BLOCK]><![endif]-->    <svg class="fi-sidebar-item-icon h-6 w-6 text-primary-600 dark:text-primary-400" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M520 456V216h320v240H520ZM120 616V216h320v400H120Zm400 320V536h320v400H520Zm-400 0V696h320v240H120Zm80-400h160V296H200v240Zm400 320h160V616H600v240Zm0-480h160v-80H600v80ZM200 856h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360 776Z"></path></svg><!--[if ENDBLOCK]><![endif]-->


        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-primary-600 dark:text-primary-400">
            Dashboard
        </span>

            </a>

    </li>
            </ul>
</li>
                            <li x-data="{ label: 'Website' }" data-group-label="Website" class="fi-sidebar-group flex flex-col gap-y-1">
            <div x-on:click="$store.sidebar.toggleCollapsedGroup(label)" class="fi-sidebar-group-button flex items-center gap-x-3 px-2 py-2 cursor-pointer">
                            <!--[if BLOCK]><![endif]-->    <svg class="fi-sidebar-group-icon h-6 w-6 text-gray-400 dark:text-gray-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M480 976q-83 0-156-31.5T197 859q-54-54-85.5-127T80 576q0-83 31.5-156T197 293q54-54 127-85.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 83-31.5 156T763 859q-54 54-127 85.5T480 976Zm-40-82v-78q-33 0-56.5-23.5T360 736v-40L168 504q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440 894Zm276-102q20-22 36-47.5t26.5-53q10.5-27.5 16-56.5t5.5-59q0-98-54.5-179T600 280v16q0 33-23.5 56.5T520 376h-80v80q0 17-11.5 28.5T400 496h-80v80h240q17 0 28.5 11.5T600 616v120h40q26 0 47 15.5t29 40.5Z"></path></svg><!--[if ENDBLOCK]><![endif]-->

            <span class="fi-sidebar-group-label flex-1 text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                Website
            </span>

                            <!--[if BLOCK]><![endif]-->    <button style="--c-300:var(--gray-300);--c-400:var(--gray-400);--c-500:var(--gray-500);--c-600:var(--gray-600);" class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus-visible:ring-2 -m-2 h-9 w-9 text-gray-400 hover:text-gray-500 focus-visible:ring-primary-600 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:ring-primary-500 fi-color-gray fi-sidebar-group-collapse-button" title="Website" type="button" x-bind:aria-expanded="! $store.sidebar.groupIsCollapsed(label)" x-on:click.stop="$store.sidebar.toggleCollapsedGroup(label)" x-bind:class="{ '-rotate-180': $store.sidebar.groupIsCollapsed(label) }" aria-expanded="true">
        <!--[if BLOCK]><![endif]-->            <span class="sr-only">
                Website
            </span>
        <!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]-->    <svg class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z" clip-rule="evenodd"></path>
</svg><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
    </button>
<!--[if ENDBLOCK]><![endif]-->
                    </div>


    <ul x-show="! $store.sidebar.groupIsCollapsed(label)" x-collapse.duration.200ms="" class="fi-sidebar-group-items flex flex-col gap-y-1">

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/pages" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Pages
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/posts" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Posts
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/categories" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>


                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Categories
        </span>

            </a>

    </li>
            </ul>
</li>
                            <li x-data="{ label: 'Shop' }" data-group-label="Shop" class="fi-sidebar-group flex flex-col gap-y-1">
            <div x-on:click="$store.sidebar.toggleCollapsedGroup(label)" class="fi-sidebar-group-button flex items-center gap-x-3 px-2 py-2 cursor-pointer">
                            <!--[if BLOCK]><![endif]-->    <svg class="fi-sidebar-group-icon h-6 w-6 text-gray-400 dark:text-gray-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M240 976q-33 0-56.5-23.5T160 896V416q0-33 23.5-56.5T240 336h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800 416v480q0 33-23.5 56.5T720 976H240Zm0-80h480V416h-80v80q0 17-11.5 28.5T600 536q-17 0-28.5-11.5T560 496v-80H400v80q0 17-11.5 28.5T360 536q-17 0-28.5-11.5T320 496v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480 256q-33 0-56.5 23.5T400 336ZM240 896V416v480Z"></path></svg><!--[if ENDBLOCK]><![endif]-->

            <span class="fi-sidebar-group-label flex-1 text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                Shop
            </span>

                            <!--[if BLOCK]><![endif]-->    <button style="--c-300:var(--gray-300);--c-400:var(--gray-400);--c-500:var(--gray-500);--c-600:var(--gray-600);" class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus-visible:ring-2 -m-2 h-9 w-9 text-gray-400 hover:text-gray-500 focus-visible:ring-primary-600 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:ring-primary-500 fi-color-gray fi-sidebar-group-collapse-button" title="Shop" type="button" x-bind:aria-expanded="! $store.sidebar.groupIsCollapsed(label)" x-on:click.stop="$store.sidebar.toggleCollapsedGroup(label)" x-bind:class="{ '-rotate-180': $store.sidebar.groupIsCollapsed(label) }" aria-expanded="true">
        <!--[if BLOCK]><![endif]-->            <span class="sr-only">
                Shop
            </span>
        <!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]-->    <svg class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z" clip-rule="evenodd"></path>
</svg><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
    </button>
<!--[if ENDBLOCK]><![endif]-->
                    </div>


    <ul x-show="! $store.sidebar.groupIsCollapsed(label)" x-collapse.duration.200ms="" class="fi-sidebar-group-items flex flex-col gap-y-1">

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/products" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Products
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/orders" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Orders
        </span>

                    <span>
                <span x-data="{}" x-tooltip="{
            content: 'New orders',
            theme: $store.theme,
        }" style="--c-400:var(--primary-400);--c-100:var(--primary-100);--c-300:var(--primary-300);--c-700:var(--primary-700);" class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-primary">
    <!--[if BLOCK]><![endif]-->        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
    <!--[if ENDBLOCK]><![endif]-->

    <span class="grid">
        <span class="truncate">
            0
        </span>
    </span>

    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
</span>
            </span>
            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/shop-categories" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Categories
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/customers" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Customers
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/payment-providers" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Payment Providers
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/payments" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Payments
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/shipping-providers" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Shipping Providers
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/taxes" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Taxes
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/offers" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Offers
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/coupons" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                                    <div class="absolute -bottom-1/2 top-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>

                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Coupons
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/invoices" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">
                                    <div class="absolute -top-1/2 bottom-1/2 w-px bg-gray-300 dark:bg-gray-600"></div>


                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Invoices
        </span>

            </a>

    </li>
            </ul>
</li>
                            <li x-data="{ label: 'Other' }" data-group-label="Other" class="fi-sidebar-group flex flex-col gap-y-1">
            <div class="fi-sidebar-group-button flex items-center gap-x-3 px-2 py-2">

            <span class="fi-sidebar-group-label flex-1 text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                Other
            </span>

                    </div>


    <ul x-show="! $store.sidebar.groupIsCollapsed(label)" x-collapse.duration.200ms="" class="fi-sidebar-group-items flex flex-col gap-y-1">

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/marketplace" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">
                    <!--[if BLOCK]><![endif]-->    <svg class="fi-sidebar-item-icon h-6 w-6 text-gray-400 dark:text-gray-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M841 538v318q0 33-23.5 56.5T761 936H201q-33 0-56.5-23.5T121 856V538q-23-21-35.5-54t-.5-72l42-136q8-26 28.5-43t47.5-17h556q27 0 47 16.5t29 43.5l42 136q12 39-.5 71T841 538Zm-272-42q27 0 41-18.5t11-41.5l-22-140h-78v148q0 21 14 36.5t34 15.5Zm-180 0q23 0 37.5-15.5T441 444V296h-78l-22 140q-4 24 10.5 42t37.5 18Zm-178 0q18 0 31.5-13t16.5-33l22-154h-78l-40 134q-6 20 6.5 43t41.5 23Zm540 0q29 0 42-23t6-43l-42-134h-76l22 154q3 20 16.5 33t31.5 13ZM201 856h560V574q-5 2-6.5 2H751q-27 0-47.5-9T663 538q-18 18-41 28t-49 10q-27 0-50.5-10T481 538q-17 18-39.5 28T393 576q-29 0-52.5-10T299 538q-21 21-41.5 29.5T211 576h-4.5q-2.5 0-5.5-2v282Zm560 0H201h560Z"></path></svg><!--[if ENDBLOCK]><![endif]-->


        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Marketplace
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/modules" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">
                    <!--[if BLOCK]><![endif]-->    <svg class="fi-sidebar-item-icon h-6 w-6 text-gray-400 dark:text-gray-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="m390 976-68-120H190l-90-160 68-120-68-120 90-160h132l68-120h180l68 120h132l90 160-68 120 68 120-90 160H638l-68 120H390Zm248-440h86l44-80-44-80h-86l-45 80 45 80ZM438 656h84l45-80-45-80h-84l-45 80 45 80Zm0-240h84l46-81-45-79h-86l-45 79 46 81ZM237 536h85l45-80-45-80h-85l-45 80 45 80Zm0 240h85l45-80-45-80h-86l-44 80 45 80Zm200 120h86l45-79-46-81h-84l-46 81 45 79Zm201-120h85l45-80-45-80h-85l-45 80 45 80Z"></path></svg><!--[if ENDBLOCK]><![endif]-->


        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Modules
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/newsletter" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">
                    <!--[if BLOCK]><![endif]-->    <svg class="fi-sidebar-item-icon h-6 w-6 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
  <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46"></path>
</svg><!--[if ENDBLOCK]><![endif]-->


        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            E-mail Marketing
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/settings" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">
                    <!--[if BLOCK]><![endif]-->    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
<svg class="fi-sidebar-item-icon h-6 w-6 text-gray-400 dark:text-gray-500" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 8.25C9.92894 8.25 8.25 9.92893 8.25 12C8.25 14.0711 9.92894 15.75 12 15.75C14.0711 15.75 15.75 14.0711 15.75 12C15.75 9.92893 14.0711 8.25 12 8.25ZM9.75 12C9.75 10.7574 10.7574 9.75 12 9.75C13.2426 9.75 14.25 10.7574 14.25 12C14.25 13.2426 13.2426 14.25 12 14.25C10.7574 14.25 9.75 13.2426 9.75 12Z" fill="currentColor"></path>
<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9747 1.25C11.5303 1.24999 11.1592 1.24999 10.8546 1.27077C10.5375 1.29241 10.238 1.33905 9.94761 1.45933C9.27379 1.73844 8.73843 2.27379 8.45932 2.94762C8.31402 3.29842 8.27467 3.66812 8.25964 4.06996C8.24756 4.39299 8.08454 4.66251 7.84395 4.80141C7.60337 4.94031 7.28845 4.94673 7.00266 4.79568C6.64714 4.60777 6.30729 4.45699 5.93083 4.40743C5.20773 4.31223 4.47642 4.50819 3.89779 4.95219C3.64843 5.14353 3.45827 5.3796 3.28099 5.6434C3.11068 5.89681 2.92517 6.21815 2.70294 6.60307L2.67769 6.64681C2.45545 7.03172 2.26993 7.35304 2.13562 7.62723C1.99581 7.91267 1.88644 8.19539 1.84541 8.50701C1.75021 9.23012 1.94617 9.96142 2.39016 10.5401C2.62128 10.8412 2.92173 11.0602 3.26217 11.2741C3.53595 11.4461 3.68788 11.7221 3.68786 12C3.68785 12.2778 3.53592 12.5538 3.26217 12.7258C2.92169 12.9397 2.62121 13.1587 2.39007 13.4599C1.94607 14.0385 1.75012 14.7698 1.84531 15.4929C1.88634 15.8045 1.99571 16.0873 2.13552 16.3727C2.26983 16.6469 2.45535 16.9682 2.67758 17.3531L2.70284 17.3969C2.92507 17.7818 3.11058 18.1031 3.28089 18.3565C3.45817 18.6203 3.64833 18.8564 3.89769 19.0477C4.47632 19.4917 5.20763 19.6877 5.93073 19.5925C6.30717 19.5429 6.647 19.3922 7.0025 19.2043C7.28833 19.0532 7.60329 19.0596 7.8439 19.1986C8.08452 19.3375 8.24756 19.607 8.25964 19.9301C8.27467 20.3319 8.31403 20.7016 8.45932 21.0524C8.73843 21.7262 9.27379 22.2616 9.94761 22.5407C10.238 22.661 10.5375 22.7076 10.8546 22.7292C11.1592 22.75 11.5303 22.75 11.9747 22.75H12.0252C12.4697 22.75 12.8407 22.75 13.1454 22.7292C13.4625 22.7076 13.762 22.661 14.0524 22.5407C14.7262 22.2616 15.2616 21.7262 15.5407 21.0524C15.686 20.7016 15.7253 20.3319 15.7403 19.93C15.7524 19.607 15.9154 19.3375 16.156 19.1985C16.3966 19.0596 16.7116 19.0532 16.9974 19.2042C17.3529 19.3921 17.6927 19.5429 18.0692 19.5924C18.7923 19.6876 19.5236 19.4917 20.1022 19.0477C20.3516 18.8563 20.5417 18.6203 20.719 18.3565C20.8893 18.1031 21.0748 17.7818 21.297 17.3969L21.3223 17.3531C21.5445 16.9682 21.7301 16.6468 21.8644 16.3726C22.0042 16.0872 22.1135 15.8045 22.1546 15.4929C22.2498 14.7697 22.0538 14.0384 21.6098 13.4598C21.3787 13.1586 21.0782 12.9397 20.7378 12.7258C20.464 12.5538 20.3121 12.2778 20.3121 11.9999C20.3121 11.7221 20.464 11.4462 20.7377 11.2742C21.0783 11.0603 21.3788 10.8414 21.6099 10.5401C22.0539 9.96149 22.2499 9.23019 22.1547 8.50708C22.1136 8.19546 22.0043 7.91274 21.8645 7.6273C21.7302 7.35313 21.5447 7.03183 21.3224 6.64695L21.2972 6.60318C21.0749 6.21825 20.8894 5.89688 20.7191 5.64347C20.5418 5.37967 20.3517 5.1436 20.1023 4.95225C19.5237 4.50826 18.7924 4.3123 18.0692 4.4075C17.6928 4.45706 17.353 4.60782 16.9975 4.79572C16.7117 4.94679 16.3967 4.94036 16.1561 4.80144C15.9155 4.66253 15.7524 4.39297 15.7403 4.06991C15.7253 3.66808 15.686 3.2984 15.5407 2.94762C15.2616 2.27379 14.7262 1.73844 14.0524 1.45933C13.762 1.33905 13.4625 1.29241 13.1454 1.27077C12.8407 1.24999 12.4697 1.24999 12.0252 1.25H11.9747ZM10.5216 2.84515C10.5988 2.81319 10.716 2.78372 10.9567 2.76729C11.2042 2.75041 11.5238 2.75 12 2.75C12.4762 2.75 12.7958 2.75041 13.0432 2.76729C13.284 2.78372 13.4012 2.81319 13.4783 2.84515C13.7846 2.97202 14.028 3.21536 14.1548 3.52165C14.1949 3.61826 14.228 3.76887 14.2414 4.12597C14.271 4.91835 14.68 5.68129 15.4061 6.10048C16.1321 6.51968 16.9974 6.4924 17.6984 6.12188C18.0143 5.9549 18.1614 5.90832 18.265 5.89467C18.5937 5.8514 18.9261 5.94047 19.1891 6.14228C19.2554 6.19312 19.3395 6.27989 19.4741 6.48016C19.6125 6.68603 19.7726 6.9626 20.0107 7.375C20.2488 7.78741 20.4083 8.06438 20.5174 8.28713C20.6235 8.50382 20.6566 8.62007 20.6675 8.70287C20.7108 9.03155 20.6217 9.36397 20.4199 9.62698C20.3562 9.70995 20.2424 9.81399 19.9397 10.0041C19.2684 10.426 18.8122 11.1616 18.8121 11.9999C18.8121 12.8383 19.2683 13.574 19.9397 13.9959C20.2423 14.186 20.3561 14.29 20.4198 14.373C20.6216 14.636 20.7107 14.9684 20.6674 15.2971C20.6565 15.3799 20.6234 15.4961 20.5173 15.7128C20.4082 15.9355 20.2487 16.2125 20.0106 16.6249C19.7725 17.0373 19.6124 17.3139 19.474 17.5198C19.3394 17.72 19.2553 17.8068 19.189 17.8576C18.926 18.0595 18.5936 18.1485 18.2649 18.1053C18.1613 18.0916 18.0142 18.045 17.6983 17.8781C16.9973 17.5075 16.132 17.4803 15.4059 17.8995C14.68 18.3187 14.271 19.0816 14.2414 19.874C14.228 20.2311 14.1949 20.3817 14.1548 20.4784C14.028 20.7846 13.7846 21.028 13.4783 21.1549C13.4012 21.1868 13.284 21.2163 13.0432 21.2327C12.7958 21.2496 12.4762 21.25 12 21.25C11.5238 21.25 11.2042 21.2496 10.9567 21.2327C10.716 21.2163 10.5988 21.1868 10.5216 21.1549C10.2154 21.028 9.97201 20.7846 9.84514 20.4784C9.80512 20.3817 9.77195 20.2311 9.75859 19.874C9.72896 19.0817 9.31997 18.3187 8.5939 17.8995C7.86784 17.4803 7.00262 17.5076 6.30158 17.8781C5.98565 18.0451 5.83863 18.0917 5.73495 18.1053C5.40626 18.1486 5.07385 18.0595 4.81084 17.8577C4.74458 17.8069 4.66045 17.7201 4.52586 17.5198C4.38751 17.314 4.22736 17.0374 3.98926 16.625C3.75115 16.2126 3.59171 15.9356 3.4826 15.7129C3.37646 15.4962 3.34338 15.3799 3.33248 15.2971C3.28921 14.9684 3.37828 14.636 3.5801 14.373C3.64376 14.2901 3.75761 14.186 4.0602 13.9959C4.73158 13.5741 5.18782 12.8384 5.18786 12.0001C5.18791 11.1616 4.73165 10.4259 4.06021 10.004C3.75769 9.81389 3.64385 9.70987 3.58019 9.62691C3.37838 9.3639 3.28931 9.03149 3.33258 8.7028C3.34348 8.62001 3.37656 8.50375 3.4827 8.28707C3.59181 8.06431 3.75125 7.78734 3.98935 7.37493C4.22746 6.96253 4.3876 6.68596 4.52596 6.48009C4.66055 6.27983 4.74468 6.19305 4.81093 6.14222C5.07395 5.9404 5.40636 5.85133 5.73504 5.8946C5.83873 5.90825 5.98576 5.95483 6.30173 6.12184C7.00273 6.49235 7.86791 6.51962 8.59394 6.10045C9.31998 5.68128 9.72896 4.91837 9.75859 4.12602C9.77195 3.76889 9.80512 3.61827 9.84514 3.52165C9.97201 3.21536 10.2154 2.97202 10.5216 2.84515Z" fill="currentColor"></path>
</svg><!--[if ENDBLOCK]><![endif]-->


        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Settings
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/users" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">
                    <!--[if BLOCK]><![endif]-->    <svg class="fi-sidebar-item-icon h-6 w-6 text-gray-400 dark:text-gray-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M400 576q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM80 896V784q0-33 17-62t47-44q51-26 115-44t141-18h14q6 0 12 2-8 18-13.5 37.5T404 696h-4q-71 0-127.5 18T180 750q-9 5-14.5 14t-5.5 20v32h252q6 21 16 41.5t22 38.5H80Zm560 40-12-60q-12-5-22.5-10.5T584 852l-58 18-40-68 46-40q-2-14-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T628 596l12-60h80l12 60q12 5 22.5 11t21.5 15l58-20 40 70-46 40q2 12 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T732 876l-12 60h-80Zm40-120q33 0 56.5-23.5T760 736q0-33-23.5-56.5T680 656q-33 0-56.5 23.5T600 736q0 33 23.5 56.5T680 816ZM400 496q33 0 56.5-23.5T480 416q0-33-23.5-56.5T400 336q-33 0-56.5 23.5T320 416q0 33 23.5 56.5T400 496Zm0-80Zm12 400Z"></path></svg><!--[if ENDBLOCK]><![endif]-->


        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Users
        </span>

            </a>

    </li>

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/kitchen-sink" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">
                    <!--[if BLOCK]><![endif]-->    <svg class="fi-sidebar-item-icon h-6 w-6 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path>
</svg><!--[if ENDBLOCK]><![endif]-->


        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Kitchen Sink
        </span>

            </a>

    </li>
            </ul>
</li>
                            <li x-data="{ label: 'Modules' }" data-group-label="Modules" class="fi-sidebar-group flex flex-col gap-y-1">
            <div x-on:click="$store.sidebar.toggleCollapsedGroup(label)" class="fi-sidebar-group-button flex items-center gap-x-3 px-2 py-2 cursor-pointer">

            <span class="fi-sidebar-group-label flex-1 text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                Modules
            </span>

                            <!--[if BLOCK]><![endif]-->    <button style="--c-300:var(--gray-300);--c-400:var(--gray-400);--c-500:var(--gray-500);--c-600:var(--gray-600);" class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus-visible:ring-2 -m-2 h-9 w-9 text-gray-400 hover:text-gray-500 focus-visible:ring-primary-600 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:ring-primary-500 fi-color-gray fi-sidebar-group-collapse-button" title="Modules" type="button" x-bind:aria-expanded="! $store.sidebar.groupIsCollapsed(label)" x-on:click.stop="$store.sidebar.toggleCollapsedGroup(label)" x-bind:class="{ '-rotate-180': $store.sidebar.groupIsCollapsed(label) }" aria-expanded="true">
        <!--[if BLOCK]><![endif]-->            <span class="sr-only">
                Modules
            </span>
        <!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]-->    <svg class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z" clip-rule="evenodd"></path>
</svg><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
    </button>
<!--[if ENDBLOCK]><![endif]-->
                    </div>


    <ul x-show="! $store.sidebar.groupIsCollapsed(label)" x-collapse.duration.200ms="" class="fi-sidebar-group-items flex flex-col gap-y-1">

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/comments-module-settings-admin" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">
                    <!--[if BLOCK]><![endif]-->    <svg class="fi-sidebar-item-icon h-6 w-6 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
  <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"></path>
</svg><!--[if ENDBLOCK]><![endif]-->


        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Comments Settings
        </span>

            </a>

    </li>
            </ul>
</li>
                            <li x-data="{ label: 'System' }" data-group-label="System" class="fi-sidebar-group flex flex-col gap-y-1">
            <div x-on:click="$store.sidebar.toggleCollapsedGroup(label)" class="fi-sidebar-group-button flex items-center gap-x-3 px-2 py-2 cursor-pointer">

            <span class="fi-sidebar-group-label flex-1 text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                System
            </span>

                            <!--[if BLOCK]><![endif]-->    <button style="--c-300:var(--gray-300);--c-400:var(--gray-400);--c-500:var(--gray-500);--c-600:var(--gray-600);" class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus-visible:ring-2 -m-2 h-9 w-9 text-gray-400 hover:text-gray-500 focus-visible:ring-primary-600 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:ring-primary-500 fi-color-gray fi-sidebar-group-collapse-button" title="System" type="button" x-bind:aria-expanded="! $store.sidebar.groupIsCollapsed(label)" x-on:click.stop="$store.sidebar.toggleCollapsedGroup(label)" x-bind:class="{ '-rotate-180': $store.sidebar.groupIsCollapsed(label) }" aria-expanded="true">
        <!--[if BLOCK]><![endif]-->            <span class="sr-only">
                System
            </span>
        <!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]-->    <svg class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z" clip-rule="evenodd"></path>
</svg><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
    </button>
<!--[if ENDBLOCK]><![endif]-->
                    </div>


    <ul x-show="! $store.sidebar.groupIsCollapsed(label)" x-collapse.duration.200ms="" class="fi-sidebar-group-items flex flex-col gap-y-1">

            <li class="fi-sidebar-item">
    <a href="http://127.0.0.2/public/admin/backups" x-on:click="window.matchMedia(`(max-width: 1024px)`).matches &amp;&amp; $store.sidebar.close()" class="fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">

                    <div class="fi-sidebar-item-grouped-border relative flex h-6 w-6 items-center justify-center">


                <div class="relative h-1.5 w-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></div>
            </div>

        <span class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200">
            Backups
        </span>

            </a>

    </li>
            </ul>
</li>
                    </ul>

        <script>
            var collapsedGroups = JSON.parse(
                localStorage.getItem('collapsedGroups'),
            )

            if (collapsedGroups === null || collapsedGroups === 'null') {
                localStorage.setItem(
                    'collapsedGroups',
                    JSON.stringify([]),
                )
            }

            collapsedGroups = JSON.parse(
                localStorage.getItem('collapsedGroups'),
            )

            document
                .querySelectorAll('.fi-sidebar-group')
                .forEach((group) => {
                    if (
                        !collapsedGroups.includes(group.dataset.groupLabel)
                    ) {
                        return
                    }

                    // Alpine.js loads too slow, so attempt to hide a
                    // collapsed sidebar group earlier.
                    group.querySelector(
                        '.fi-sidebar-group-items',
                    ).style.display = 'none'
                    group
                        .querySelector('.fi-sidebar-group-collapse-button')
                        .classList.add('rotate-180')
                })
        </script>


    </nav>


</aside>


        <div id="live-edit-frame-holder">

        </div>



<div>
        <?php //print mw_admin_footer_scripts(); ?>
</div>
        <script>

            mw.settings.adminUrl = '<?php print admin_url(); ?>';
            mw.settings.liveEditModuleSettingsUrls =  <?php print json_encode(\MicroweberPackages\Module\Facades\ModuleAdmin::getLiveEditSettingsUrls()); ?>;
            mw.settings.liveEditModuleSettingsComponents=  <?php print json_encode(\MicroweberPackages\Module\Facades\ModuleAdmin::getSettingsComponents()); ?>;
            mw.settings.liveEditModuleSettingsComponentsFromModuleRepository=  <?php print json_encode(\MicroweberPackages\Microweber\Facades\Microweber::getSettingsComponents()); ?>;

        </script>

         <script src="{{ asset('vendor/microweber-packages/frontend-assets/build/live-edit-app.js') }}"></script>
        <?php  print \MicroweberPackages\LiveEdit\Facades\LiveEditManager::headTags(); ?>
        <?php event_trigger('mw.live_edit.footer'); ?>
    </div>



    <x-filament-actions::modals />

</div>
