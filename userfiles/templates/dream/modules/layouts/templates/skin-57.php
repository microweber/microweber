<?php

/*

type: layout

name: Gallery With Categories

position: 57

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '4';
}
?>

<?php /*
<div class=" nodrop edit <?php print $padding ?>" field="layout-skin-57-<?php print $params['id'] ?>" rel="module">
    <section class="height-70 safe-mode bg--dark imagebg page-title page-title--animate parallax" data-overlay="<?php print $overlay; ?>">
        <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero21.jpg');"></div>

        <div class="container pos-vertical-center">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1 text-center allow-drop">
                    <h1>Digital Delivered.</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="masonry-contained section--overlap">
        <div class="container">
            <div class="row">
                <div class="masonry box-shadow-wide">
                    <div class="masonry__filters masonry__filters--outside text-center" data-filter-all-text="Show All"></div>
                    <div class="masonry__container masonry--gapless masonry--animate">
                        <div class="col-sm-6 masonry__item" data-masonry-filter="digital">
                            <a href="#">
                                <div class="hover-element hover-element-1" data-title-position="top,right">
                                    <div class="hover-element__initial">
                                        <img alt="Pic" src="<?php print template_url('assets/img/'); ?>work6.jpg"/>
                                    </div>
                                    <div class="hover-element__reveal" data-overlay="9">
                                        <div class="boxed">
                                            <h5>Freehance</h5>
                                            <span>
                                                        <em>iOS Application</em>
                                                    </span>
                                        </div>
                                    </div>
                                </div>
                                <!--end hover element-->
                            </a>
                        </div>
                        <div class="col-sm-6 masonry__item" data-masonry-filter="branding">
                            <a href="#">
                                <div class="hover-element hover-element-1" data-title-position="top,right">
                                    <div class="hover-element__initial">
                                        <img alt="Pic" src="<?php print template_url('assets/img/'); ?>work2.jpg"/>
                                    </div>
                                    <div class="hover-element__reveal" data-overlay="9">
                                        <div class="boxed">
                                            <h5>Michael Andrews</h5>
                                            <span>
                                                        <em>Branding & Identity</em>
                                                    </span>
                                        </div>
                                    </div>
                                </div>
                                <!--end hover element-->
                            </a>
                        </div>
                        <div class="col-sm-6 masonry__item" data-masonry-filter="packaging">
                            <a href="#">
                                <div class="hover-element hover-element-1" data-title-position="top,right">
                                    <div class="hover-element__initial">
                                        <img alt="Pic" src="<?php print template_url('assets/img/'); ?>work5.jpg"/>
                                    </div>
                                    <div class="hover-element__reveal" data-overlay="9">
                                        <div class="boxed">
                                            <h5>Authentic Apparel</h5>
                                            <span>
                                                        <em>Packaging Design</em>
                                                    </span>
                                        </div>
                                    </div>
                                </div>
                                <!--end hover element-->
                            </a>
                        </div>
                        <div class="col-sm-6 masonry__item" data-masonry-filter="branding">
                            <a href="#">
                                <div class="hover-element hover-element-1" data-title-position="top,right">
                                    <div class="hover-element__initial">
                                        <img alt="Pic" src="<?php print template_url('assets/img/'); ?>work10.jpg"/>
                                    </div>
                                    <div class="hover-element__reveal" data-overlay="9">
                                        <div class="boxed">
                                            <h5>Wave Poster</h5>
                                            <span>
                                                        <em>Logo Design</em>
                                                    </span>
                                        </div>
                                    </div>
                                </div>
                                <!--end hover element-->
                            </a>
                        </div>
                        <div class="col-sm-6 masonry__item" data-masonry-filter="digital">
                            <a href="#">
                                <div class="hover-element hover-element-1" data-title-position="top,right">
                                    <div class="hover-element__initial">
                                        <img alt="Pic" src="<?php print template_url('assets/img/'); ?>work12.jpg"/>
                                    </div>
                                    <div class="hover-element__reveal" data-overlay="9">
                                        <div class="boxed">
                                            <h5>Tesla Controller</h5>
                                            <span>
                                                        <em>Apple Watch Application</em>
                                                    </span>
                                        </div>
                                    </div>
                                </div>
                                <!--end hover element-->
                            </a>
                        </div>
                        <div class="col-sm-6 masonry__item" data-masonry-filter="digital">
                            <a href="#">
                                <div class="hover-element hover-element-1" data-title-position="top,right">
                                    <div class="hover-element__initial">
                                        <img alt="Pic" src="<?php print template_url('assets/img/'); ?>work14.jpg"/>
                                    </div>
                                    <div class="hover-element__reveal" data-overlay="9">
                                        <div class="boxed">
                                            <h5>Pillar</h5>
                                            <span>
                                                        <em>Website & Digital</em>
                                                    </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>*/
?>