<?php

/*

type: layout

name: Cover YouTube Background

position: 30

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '5';
}
?>

<?php
$youtube = get_option('youtube', $params['id']);
if ($youtube == false) {
    $youtube = 'LcsOh3Jz9Cg';
}
?>

<script src="<?php print template_url(); ?>assets/js/scripts.js"></script>

<section class="imagebg safe-mode videobg height-100 nodrop edit <?php print $padding ?>" field="layout-skin-29-<?php print $params['id'] ?>" rel="module" data-overlay="<?php print $overlay; ?>">
    <div class="youtube-background" data-video-url="<?php print $youtube ?>" data-start-at="0"></div>
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero27.jpg');"></div>
    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 text-center allow-drop">
                <p class="lead">
                    Dream is a Chicago-based collective of passionate designers, developers and brand strategists offering insight to some of the world's most admired brands.
                </p>
            </div>
        </div>
    </div>
</section>