@php
/*

type: layout

name: Contacts 22

position: 22

categories: Contact Us

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .contacts-22 .contacts-22-form-title {
        text-align: center;
        margin-bottom: 50px;
    }

    .contacts-22 .contacts-22-form {
        padding: 60px 120px;
        background-color: #f9f9f9;
        border-bottom-right-radius: 23px;
        border-bottom-left-radius: 23px;
    }

    @media (max-width: 992px) {
        .contacts-22 .contacts-22-form {
            padding: 45px 30px;
        }
    }

    .google-map-22 iframe {
        border-top-left-radius: 23px;
        border-top-right-radius: 23px;
    }

    .contacts-22 em {
        font-style: normal;
        color: var(--mw-primary-color);
    }
</style>

<section class="section contacts-22">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />

    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />

        <div class="mw-layout-container no-element container edit safe-mode " field="layout-contacts-skin-22-{{ $params['id'] ?? '' }}" rel="module">
            <div class="row">
                <div class="col-lg-12 px-0 google-map-22 allow-select">
                    <module type="google_maps" id="google-maps-{{ $params['id'] ?? '' }}" />
                </div>

                <div class="col-lg-12 contacts-22-form allow-select">
                    <div class="col-lg-12">
                        <h4 class="contacts-22-form-title">Make Your <em>Reservation</em> Through This <em>Form</em></h4>
                    </div>

                    <module type="contact_form" template="skin-5" id="contact-form-{{ $params['id'] ?? '' }}"/>
                </div>
            </div>
        </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />

</section>
