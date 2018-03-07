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


<div class="nodrop edit <?php print $padding ?>" field="layout-skin-57-<?php print $params['id'] ?>" rel="module">
    <section class="height-70 safe-mode bg--dark imagebg page-title page-title--animate parallax"
             data-overlay="<?php print $overlay; ?>">
        <div class="background-image-holder"
             style="background-image: url('<?php print template_url('assets/img/'); ?>hero21.jpg');"></div>
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
                <module type="posts" template="skin-6"/>
            </div>
        </div>
    </section>
</div>
