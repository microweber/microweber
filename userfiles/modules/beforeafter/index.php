<?php

$before = get_option('before', $params['id']);
$after = get_option('after', $params['id']);
$rand = $params['id'];

if ($before == false) {
    $before = 'http://www.bmw.bg/content/dam/bmw/common/all-models/1-series/5-door/2015/model-card/BMW-1-Series%205-door_Modelcard.png';
    }

if ($after == false) {
    $after = 'http://www.bmw.bg/content/dam/bmw/common/all-models/1-series/5-door/2015/model-card/BMW-1-Series%205-door_Modelcard.png';
    }


?>
<script>


    mw.moduleCSS('<?php print module_url(); ?>css/twentytwenty.css');
    mw.moduleJS('<?php print module_url(); ?>js/jquery.event.move.js');
    mw.moduleJS('<?php print module_url(); ?>js/jquery.twentytwenty.js');

    $(document).ready(function () {
        mw.$("#mw-before-after-<?php print $rand; ?>").twentytwenty({default_offset_pct: 0});
    });

</script>

<div class="mw-before-after" id="mw-before-after-<?php print $params['id']; ?>">
    <style scoped="scoped">.twentytwenty-overlay {
            display: none !important;
        } </style>
    <img src="<?php print $before; ?>"/>
    <img src="<?php print $after; ?>"/>
</div>