<?php

$before = get_option('before', $params['id']);
$after = get_option('after', $params['id']);
$rand = $params['id'];

if ($before == false) {
    $before = module_url() . 'img/white-car.jpg';
}

if ($after == false) {
    $after = module_url() . 'img/blue-car.jpg';
}
?>
<script>
    mw.moduleCSS('<?php print module_url(); ?>css/twentytwenty.css');
    mw.moduleJS('<?php print module_url(); ?>js/jquery.event.move.js');
    mw.moduleJS('<?php print module_url(); ?>js/jquery.twentytwenty.js');

    $(document).ready(function () {
        preload_image = function (src) {
            var elem = document.createElement("img");
            elem.setAttribute("src", src);
        }

        mw.$("#mw-before-after-<?php print $rand; ?>").twentytwenty({default_offset_pct: 0.5});
    });
</script>

<div class="mw-before-after" id="mw-before-after-<?php print $params['id']; ?>">

    <style scoped="scoped">
        .twentytwenty-container {
            min-height: 100px !important;
        }
    </style>
    <img src="<?php print $before; ?>" alt="<?php _e('before image'); ?>"/>
    <img src="<?php print $after; ?>" alt="<?php _e('after image'); ?>"/>
</div>