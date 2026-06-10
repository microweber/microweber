@php
/*

type: layout

name: Contacts 19

position: 19

categories: Contact Us

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'mw-p-t-50';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'mw-p-b-90';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .mw-contacts-19-section-title-wrap {
        background: var(--mw-primary-color);
        border-radius: 10px;
        padding: 10px 30px;
    }

    .mw-contacts-19-menu {
        margin: 0;
        padding: 0;
    }

    .mw-contacts-19-item {
        list-style: none;
        display: inline-block;
        vertical-align: top;
    }

    .mw-contacts-19-link {
        border: 1px solid #ececec;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 20px;
        display: inline-block;
        vertical-align: top;
        text-align: center;
        margin-right: 10px;
        margin-bottom: 10px;
        padding: 6px 14px;
        min-width: 70px;
    }

    .mw-contacts-19-link:hover {
        background-color: var(--mw-primary-color);
        color: #fff;
    }

    .mw-contacts-19-contact-info {
        background: #fff;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        padding: 60px 30px 30px 30px;
        height: 100%;
    }

    .mw-contacts-19-mw-contacts-19-contact-info-border-start {
        border-right: 1px solid #ececec;
        border-radius: 10px 0 0 10px;
    }

    .avatar-image-wrapper img {
        border-radius: 100px;
        width: 160px !important;
        height: 160px !important;
        object-fit: cover;
    }
</style>

<section class="section contact contacts-19">
    <module type="background" data-background-color="#F9F9F9" id="background-layout--{{ $params['id'] ?? '' }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top"/>
    <div class="mw-layout-container no-element container edit safe-mode"
         field="layout-contacts-skin-19-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-12">
                <div class="mw-contacts-19-section-title-wrap d-flex justify-content-center background-color-element element align-items-center mb-5 avatar-image-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/aerial-view-man-using-computer-laptop-wooden-table.jpg') }}" alt=""/>
                    <h2 class="text-white ms-4 mb-0">Say Hi</h2>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-lg-3 col-md-6 col-12 pe-lg-0 background-color-element element">
                <div class="mw-contacts-19-contact-info mw-contacts-19-mw-contacts-19-contact-info-border-start d-flex flex-column">
                    <strong class="site-footer-title d-block mb-3">Services</strong>

                    <div class="mw-contacts-19-menu ">
                        <div class="mw-contacts-19-item mw-contacts-19-link cloneable background-color-element element allow-select">
                            <a>Websites</a>
                        </div>

                        <div class="mw-contacts-19-item mw-contacts-19-link cloneable background-color-element element allow-select">
                            <a>Branding</a>
                        </div>

                        <div class="mw-contacts-19-item mw-contacts-19-link cloneable background-color-element element allow-select">
                            <a>Ecommerce</a>
                        </div>

                        <div class="mw-contacts-19-item mw-contacts-19-link cloneable background-color-element element allow-select">
                            <a>SEO</a>
                        </div>
                    </div>

                    <strong class="site-footer-title d-block mt-4 mb-3">Stay connected</strong>

                    <module type="social_links" template="skin-9" id="social-links-{{ $params['id'] ?? '' }}" />

                    <strong class="site-footer-title d-block mt-4 mb-3">Start a project</strong>

                    <p class="mb-0">I'm available for freelance projects</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 ps-lg-0 background-color-element element">
                <div class="mw-contacts-19-contact-info d-flex flex-column">
                    <strong class="site-footer-title d-block mb-3">About</strong>

                    <p class="mb-2">
                        Joshua is a professional web developer. Feel free to get in touch with me.
                    </p>

                    <strong class="site-footer-title d-block mt-4 mb-3">Email</strong>

                    <p>
                        <a href="mailto:hello@josh.design">
                            hello@josh.design
                        </a>
                    </p>

                    <strong class="site-footer-title d-block mt-4 mb-3">Call</strong>

                    <p class="mb-0">
                        <a href="tel: 120-240-9600">
                            120-240-9600
                        </a>
                    </p>
                </div>
            </div>

            <div class="col-lg-6 col-12 background-color-element element">
                <module type="contact_form" template="skin-6" id="contact-form-{{ $params['id'] ?? '' }}" />
            </div>

        </div>
</section>
</div>
<module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom"/>

</section>
