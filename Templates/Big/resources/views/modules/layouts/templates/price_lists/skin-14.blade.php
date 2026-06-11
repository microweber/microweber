{{--
type: layout

    name: Price Lists 14

    position: 14

    categories: Price Lists
--}}

<style>
    .price-lists-14-wrapper {
        background-color: rgb(var(--mw-primary-color) / .6);
    }

    .price-lists-14-text {
        margin-bottom: 15px;
    }

    .price-lists-14-text h4 {
        font-weight: bold;
    }

    .price-lists-14-text span {
        font-size: 12px;
    }

    .price-lists-14-text .span-2 {
        font-size: 16px;
        font-weight: normal;
    }

    .price-lists-14-ul-wrapper ul {
        list-style: none;
        padding-left: 0;

        li a {
            line-height: 2em;
        }
    }
</style>

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background price-list-14"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] }}" />

        <module type="spacer" height="150px" id="spacer-layout--{{ $params['id'] }}-top" />
        <div class="mw-layout-container mw-layouts-full-width-container safe-mode mh-700 row align-items-center justify-content-center mw-layout-overlay-container allow-drop edit safe-mode" field="layout-skin-14-{{ $params['id'] }}" rel="module">
            <x-row>
                <div class="price-lists-14-wrapper p-lg-4 p-2 col-xl-3 col-lg-6 col-12 cloneable element background-color-element allow-select" style="background-color: color-mix(in srgb, var(--mw-primary-color) 60%, transparent);">

                    <div class="py-2">
                        <h2 class="font-weight-bold">
                            WEB
                        </h2>
                    </div>

                    <div class="price-lists-14-text py-2">
                        <h4 class="mt-2">
                            699'<span class="price-lists-14-span">99</span>
                            <span class="price-lists-14-span-2">$ for first year </span>
                        </h4>
                        <h4>
                            299'<span class="price-lists-14-span">99</span>
                            <span class="price-lists-14-span-2">$ for renewal </span>
                        </h4>
                    </div>

                    <div class="price-lists-14-ul-wrapper py-2">
                        <ul>
                            <li>
                                <a href="#no"> + Company identity</a>
                            </li>
                            <li>
                                <a href="#no"> + Custom Design</a>
                            </li>
                            <li>
                                <a href="#no"> + Monthly Backup</a>
                            </li>
                            <li>
                                <a href="#no"> + 5 GB Disk Space</a>
                            </li>
                            <li>
                                <a href="#no"> + SSL Certification</a>
                            </li>
                            <li>
                                <a href="#no"> + Up to 50 Content Updates</a>
                            </li>
                            <li>
                                <a href="#no"> + Free Email Support</a>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="price-lists-14-wrapper p-lg-4 p-2 col-xl-3 col-lg-6 col-12 cloneable element background-color-element allow-select" style="background-color: rgb(from #000 r g b / .6);">

                    <div class="py-2">
                        <h2 class="font-weight-bold">
                            WEB Plus
                        </h2>
                    </div>

                    <div class="price-lists-14-text py-2">
                        <h4 class="mt-2">
                            799'<span class="price-lists-14-span">99</span>
                            <span class="price-lists-14-span-2">$ for first year </span>
                        </h4>
                        <h4>
                            359'<span class="price-lists-14-span">99</span>
                            <span class="price-lists-14-span-2">$ for renewal </span>
                        </h4>
                    </div>

                    <div class="price-lists-14-ul-wrapper py-2">
                        <ul>
                            <li>
                                <a href="#no"> + Company identity</a>
                            </li>
                            <li>
                                <a href="#no"> + Custom Design</a>
                            </li>
                            <li>
                                <a href="#no"> + Monthly Backup</a>
                            </li>
                            <li>
                                <a href="#no"> + 5 GB Disk Space</a>
                            </li>
                            <li>
                                <a href="#no"> + SSL Certification</a>
                            </li>
                            <li>
                                <a href="#no"> + Up to 50 Content Updates</a>
                            </li>
                            <li>
                                <a href="#no"> + Free Email Support</a>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="price-lists-14-wrapper p-lg-4 p-2 col-xl-3 col-lg-6 col-12 cloneable element background-color-element allow-select" style="background-color: color-mix(in srgb, var(--mw-primary-color) 60%, transparent);">

                    <div class="py-2">
                        <h2 class="font-weight-bold">
                            WEB Store
                        </h2>
                    </div>

                    <div class="price-lists-14-text py-2">
                        <h4 class="mt-2">
                            999'<span class="price-lists-14-span">99</span>
                            <span class="price-lists-14-span-2">$ for first year </span>
                        </h4>
                        <h4>
                            499'<span class="price-lists-14-span">99</span>
                            <span class="price-lists-14-span-2">$ for renewal </span>
                        </h4>
                    </div>

                    <div class="price-lists-14-ul-wrapper py-2">
                        <ul>
                            <li>
                                <a href="#no"> + Company identity</a>
                            </li>
                            <li>
                                <a href="#no"> + Custom Design</a>
                            </li>
                            <li>
                                <a href="#no"> + Monthly Backup</a>
                            </li>
                            <li>
                                <a href="#no"> + 5 GB Disk Space</a>
                            </li>
                            <li>
                                <a href="#no"> + SSL Certification</a>
                            </li>
                            <li>
                                <a href="#no"> + Up to 50 Content Updates</a>
                            </li>
                            <li>
                                <a href="#no"> + Free Email Support</a>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="price-lists-14-wrapper p-lg-4 p-2 col-xl-3 col-lg-6 col-12 cloneable element background-color-element allow-select" style="background-color: rgb(from #000 r g b / .6);">

                    <div class="py-2">
                        <h2 class="font-weight-bold">
                            WEB Cover
                        </h2>
                    </div>

                    <div class="price-lists-14-text py-2">
                        <h4 class="mt-2">
                            1199'<span class="price-lists-14-span">99</span>
                            <span class="price-lists-14-span-2">$ for first year </span>
                        </h4>
                        <h4>
                            599'<span class="price-lists-14-span">99</span>
                            <span class="price-lists-14-span-2">$ for renewal </span>
                        </h4>
                    </div>

                    <div class="price-lists-14-ul-wrapper py-2">
                        <ul>
                            <li>
                                <a href="#no"> + Company identity</a>
                            </li>
                            <li>
                                <a href="#no"> + Custom Design</a>
                            </li>
                            <li>
                                <a href="#no"> + Monthly Backup</a>
                            </li>
                            <li>
                                <a href="#no"> + 5 GB Disk Space</a>
                            </li>
                            <li>
                                <a href="#no"> + SSL Certification</a>
                            </li>
                            <li>
                                <a href="#no"> + Up to 50 Content Updates</a>
                            </li>
                            <li>
                                <a href="#no"> + Free Email Support</a>
                            </li>
                        </ul>
                    </div>

                </div>

            </x-row>
            <div class="allow-select">
                <module type="btn" button_style="btn-primary mt-lg-3 mt-5 allow-select" text="Learn more" style="text-align: center;" />
            </div>
        </div>

        <module type="spacer" height="150px" id="spacer-layout--{{ $params['id'] }}-bottom" />
</x-layout-section>
