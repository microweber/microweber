<?php

/*

type: layout

name: Feature 67

position: 66

categories: Features

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<style>
    .feature-67 .why-box {
        padding: 30px;
        background: var(--mw-primary-color);
        color: #fff;
    }

    .feature-67 .why-box h3 {
        font-weight: 700;
        margin-bottom: 30px;
        color: #fff;
    }

    .feature-67 .why-box p {
        margin-bottom: 30px;
        color: #fff;
    }

    .feature-67 .why-box .more-btn {
        display: inline-block;
        background: rgba(255, 255, 255, 0.3);
        padding: 6px 30px 8px 30px;
        color: #fff;
        border-radius: 50px;
        transition: all ease-in-out 0.4s;
    }


    .feature-67 .why-box .more-btn:hover {
        color: var(--mw-primary-color);
        background: #fff;
    }

    .feature-67 .icon-box {
        text-align: center;
        background: #fff;
        padding: 40px 30px;
        width: 100%;
        height: 100%;
        border: 1px solid rgba(55, 55, 63, 0.1);
        transition: 0.3s;
    }

    .feature-67 .icon-box i {
        color: var(--mw-primary-color);
        margin-bottom: 30px;
        margin-bottom: 30px;
        background: rgba(206, 18, 18, 0.1);
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
    }

    .feature-67 .icon-box h4 {
        font-weight: 400;
        margin: 0 0 30px 0;
    }

    .feature-67 .icon-box p {
        color: #6c757d;
    }

    @media (min-width: 1200px) {
        .feature-67 .icon-box:hover {
            transform: scale(1.1);
        }
        .mw-live-edit {
            .feature-67 .icon-box:hover {
                transform: none !important;
            }
        }


    }
</style>

<section class="section feature-67 <?php print $layout_classes; ?> ">

    <module type="background" data-background-color="#EEEEEE" id="background-layout--{{ $params['id'] }}" />

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="mw-layout-container container no-element edit safe-mode"
         field="layout-feature-skin-67-{{ $params['id'] }}" rel="module">
        <div class="row gy-4">

            <div class="col-lg-4 cloneable element" data-aos="fade-up" data-aos-delay="100">
                <div class="why-box background-color-element element">
                    <h3>Why Choose Yummy?</h3>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Duis aute irure dolor in reprehenderit
                        Asperiores dolores sed et. Tenetur quia eos. Autem tempore quibusdam vel necessitatibus optio ad corporis.
                        Tenetur quia eos. Autem tempore quibusdam vel necessitatibus optio ad corporis. Tenetur quia eos. Autem tempore quibusdam vel necessitatibus optio ad corporis.
                    </p>
                    <div class="text-center">
                       <module type="btn" button_style="btn btn-outline" button_text="Learn More"/>
                    </div>
                </div>
            </div><!-- End Why Box -->

            <div class="col-lg-8 d-flex align-items-center">
                <div class="row gy-4">

                    <div class="col-xl-4 cloneable element" data-aos="fade-up" data-aos-delay="200">
                        <div class="icon-box d-flex flex-column justify-content-center align-items-center background-color-element element">
                            <i class="no-typing mw-micon-File-Clipboard" style="font-size: 32px;"></i>
                            <h5>Corporis voluptates officia eiusmod</h5>
                            <p>Consequuntur sunt aut quasi enim aliquam quae harum pariatur laboris nisi ut aliquip</p>
                        </div>
                    </div><!-- End Icon Box -->

                    <div class="col-xl-4 cloneable element" data-aos="fade-up" data-aos-delay="300">
                        <div class="icon-box d-flex flex-column justify-content-center align-items-center background-color-element element">
                            <i class="no-typing mw-micon-Diamond" style="font-size: 32px;"></i>
                            <h5>Ullamco laboris ladore pan</h5>
                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
                        </div>
                    </div><!-- End Icon Box -->

                    <div class="col-xl-4 cloneable element" data-aos="fade-up" data-aos-delay="400">
                        <div class="icon-box d-flex flex-column justify-content-center align-items-center background-color-element element">
                            <i class="no-typing mw-micon-Box-withFolders" style="font-size: 32px;"></i>
                            <h5>Labore consequatur incidid dolore</h5>
                            <p>Aut suscipit aut cum nemo deleniti aut omnis. Doloribus ut maiores omnis facere</p>
                        </div>
                    </div><!-- End Icon Box -->

                </div>
            </div>

        </div>

    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>

</section>
