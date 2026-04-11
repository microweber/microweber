<?php

/*

type: layout

name: Footers 1

position: 1

categories: Footers

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<section class="footer-background <?php print $layout_classes; ?> edit safe-mode"
         field="layout-footer-skin-1-{{ $params['id'] }}" rel="module">
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="mw-layout-container container">
        <div class="row text-md-start text-center">

            <div class="col-lg-5 col-12 mb-4 mb-lg-0">
                <div class="edit" field="layout-footer-skin-1-company-{{ $params['id'] }}" rel="module">
                    <p class="font-weight-bold">Website Builder and CMS</p>
                    <br>
                    <small>This is a website builder and content management system of new generation.</small>
                    <br>
                </div>
                <div class="footer-19-menu d-flex justify-content-lg-start justify-content-center ps-0 mt-3">
                    <module type="menu" template="simple" name="footer_menu" id="{{ $params['id'] }}-menu"/>
                </div>
            </div>

            <div class="col-lg-4 col-12 mb-4 mb-lg-0">
                <div class="edit" field="layout-footer-skin-1-phone-{{ $params['id'] }}" rel="module">
                    <small>Phone</small>
                    <p class="mt-2">123-456-7890</p>
                </div>
                <div class="edit" field="layout-footer-skin-1-email-{{ $params['id'] }}" rel="module">
                    <small>Email</small>
                    <p class="mt-2"><a href="">mail@yourcompany.com</a></p>
                </div>
                <div class="edit" field="layout-footer-skin-1-social-{{ $params['id'] }}" rel="module">
                    <p>Social</p>
                    <module type="social_links" template="skin-4" id="{{ $params['id'] }}-social-links"/>
                </div>
            </div>

            <div class="col-lg-3 col-12 edit" field="layout-footer-skin-1-addresses-{{ $params['id'] }}" rel="module">
                <small>California</small>
                <p class="mt-2">21 Lebsack Harbor Apt. 276 Palo Alto, CA</p>

                <small>New York</small>
                <p class="mt-2">74 Howell Islands Suite 834 Rochester, NY</p>
            </div>

        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

<section class="py-2" style="background-color: #f5f5f5;">
    <div class="container mw-layout-container py-2">
        <div class="col-12 d-md-flex text-center">
            <small class="col-sm-6 text-md-start text-center edit" field="footer-reserved-skin-1-{{ $params['id'] }}" rel="module">&copy; All Rights Reserved.</small>
            <small class="col-sm-6 mb-0 noedit text-md-end text-center"><?php print powered_by_link(); ?></small>
        </div>
    </div>
</section>
