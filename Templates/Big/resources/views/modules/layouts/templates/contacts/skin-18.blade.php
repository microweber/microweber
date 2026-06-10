@php
/*

type: layout

name: Contacts 18

position: 18

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
    .contacts-18 .section-heading p {
        margin-top: 50px;
    }

    .contacts-18 .special-offer {
        margin-top: 50px;
        background-color: #fff;
        border-radius: 25px;
        padding: 30px 120px;
        display: inline-block;
        position: relative;
    }

    .contacts-18 .special-offer .offer {
        position: absolute;
        left: 0;
        top: 0;
        background-color: var(--mw-primary-color);
        width: auto;
        padding: 30px 8px;
        line-height: 25px;
        height: 100%;
        border-radius: 15px 60px 60px 15px;
        color: #fff;
        font-size: 16px;
        text-transform: uppercase;
        text-align: left;
        display: flex;
        align-items: start;
        justify-content: center;
        flex-direction: column;
    }

    .contacts-18 .special-offer .offer em {
        font-size: 28px;
        font-style: normal;
        font-weight: 600;
    }

    .contacts-18 .special-offer .contacts-18-title-1 {
        font-size: 15px;
        color: #4a4a4a;
        text-transform: uppercase;
        font-weight: 400;
        margin-bottom: 15px;
        display: block;
    }

    .contacts-18 .special-offer .contacts-18-title-1 em {
        font-style: normal;
        color: var(--mw-primary-color);
        font-weight: 600;
    }

    .contacts-18 .special-offer .contacts-18-title-2 {
        font-size: 22px;
        font-weight: 600;
        display: block;
    }

    .contacts-18 .special-offer .contacts-18-title-2 em {
        font-style: normal;
        color: var(--mw-primary-color);
    }

    .contacts-18 .special-offer .contacts-8-right-arrow-wrapper  > *,
    .contacts-18 .special-offer .contacts-8-right-arrow-wrapper  > *:before,
    .contacts-18 .special-offer .contacts-8-right-arrow-wrapper  > *:after{
        color: inherit;
    }
    .contacts-18 .special-offer .contacts-8-right-arrow-wrapper  > * {
        position: absolute;
        inset:0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .contacts-18 .special-offer .contacts-8-right-arrow-wrapper {
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        display: inline-flex;
        align-items:center;
        justify-content: center;
        background-color: #F1F0FE;
        color: #111;
        border-radius: 50%;
        width: auto;
        height: auto;
        font-size: 36px;
        aspect-ratio: 1 / 1;
        line-height: 0;
        padding: .6em;

    }



    .contacts-18 .contacts-18-content {
        border-radius: 25px;
        background-color: var(--mw-primary-color);
        position: relative;
        z-index: 1;
    }
</style>

<section class="section contacts-18">
    <module type="background" data-background-color="#F1F0FE" id="background-layout--{{ $params['id'] ?? '' }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top"/>
    <div class="mw-layout-container no-element container edit safe-mode "
         field="layout-contacts-skin-18-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row align-items-center">
            <div class="col-lg-7 align-self-stretch p-5 allow-select allow-drop">
                <div class="section-heading">
                    <div class="regular-mode">
                        <h6>Contact Us</h6>
                        <h2>Feel free to contact us anytime</h2>
                        <p>Thank you for choosing our templates. We provide you best CSS templates at absolutely 100%
                            free of charge. You may support us by sharing our website to your friends.</p>
                    </div>
                    <a>
                        <div class="special-offer background-color-element element">
                            <div class="offer background-color-element element">off<br><em>50%</em></div>
                            <div class="contacts-18-title-1">Valide: <em>24 April 2036</em></div>
                            <div class="contacts-18-title-2">Special Offer <em>50%</em> OFF!</div>
                            <div class="contacts-8-right-arrow-wrapper background-color-element no-element nodrop no-drag fa fa-angle-right icon no-typing">

                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-5 allow-select allow-drop align-self-stretch">
                <div class="contacts-18-content p-5">
                    <module type="contact_form" template="skin-1"/>
                </div>
            </div>
        </div>

    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom"/>

</section>
