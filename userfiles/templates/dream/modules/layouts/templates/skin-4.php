<?php

/*

type: layout

name: 4 Columns icons with Parallax

position: 4

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_parallax_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '3';
}
?>

<section class="imagebg safe-mode section--even stats-1 <?php if($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop edit <?php print $padding ?>" data-overlay="<?php print $overlay; ?>" field="layout-skin-4-<?php print $params['id'] ?>"
         rel="module">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero2.jpg');"></div>

    <div class="row wide-grid">
        <div class="col-sm-3 col-xs-6 cloneable">
            <div class="feature feature-1 text-center">
                <i class="icon icon--lg icon-Bodybuilding safe-element"></i>
                <h3>16,000+</h3>
                <span class="safe-element"><em>Customers strong</em></span>
            </div>
        </div>

        <div class="col-sm-3 col-xs-6 cloneable">
            <div class="feature feature-1 text-center">
                <i class="icon icon--lg icon-Fingerprint safe-element"></i>
                <h3>16</h3>
                <span class="safe-element"><em>Passionate team members</em></span>
            </div>
        </div>

        <div class="col-sm-3 col-xs-6 cloneable">
            <div class="feature feature-1 text-center">
                <i class="icon icon--lg icon-Astronaut safe-element"></i>
                <h3>82</h3>
                <span class="safe-element"><em>Launched startups</em></span>
            </div>
        </div>

        <div class="col-sm-3 col-xs-6 cloneable">
            <div class="feature feature-1 text-center">
                <i class="icon icon--lg icon-Cardigan safe-element"></i>
                <h3>Zero</h3>
                <span class="safe-element"><em>Plaid cardigans</em></span>
            </div>
        </div>
    </div>
</section>