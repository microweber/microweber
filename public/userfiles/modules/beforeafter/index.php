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

    $(window).on('load',function () {
        mw.$("#mw-before-after-<?php print $rand; ?>").twentytwenty({default_offset_pct: 0.5});
    })
    $(document).ready(function () {
        mw.$("#mw-before-after-<?php print $rand; ?>").twentytwenty({default_offset_pct: 0.5});
        mw.image.preloadForAll(['<?php print $before; ?>', '<?php print $after; ?>'], undefined, function (){
            mw.$("#mw-before-after-<?php print $rand; ?>").twentytwenty({default_offset_pct: 0.5});
        })
    });
</script>

<div class="mw-before-after" id="mw-before-after-<?php print $params['id']; ?>">

    <style scoped="scoped">
        .twentytwenty-container {
            min-height: 100px !important;
        }
    </style>
    
    <img src="<?php print $before; ?>" alt="<?php _e('Before image', "modules/beforeafter"); ?>"/>
    <img src="<?php print $after; ?>" alt="<?php _e('After image', "modules/beforeafter"); ?>"/>
</div>
