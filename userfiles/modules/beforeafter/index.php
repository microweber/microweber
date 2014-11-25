

<?php

    $before =  get_option('before', $params['id']);
    $after =  get_option('after', $params['id']);
    $rand = uniqid();
?>
<script>


    mw.moduleCSS('<?php print module_url(); ?>css/twentytwenty.css');
    mw.moduleJS('<?php print module_url(); ?>js/jquery.event.move.js');
    mw.moduleJS('<?php print module_url(); ?>js/jquery.twentytwenty.js');

    $(document).ready(function(){
         mw.$("#mw-before-after-<?php print $rand; ?>").twentytwenty({default_offset_pct:0});
    });

</script>

<div class="mw-before-after" id="mw-before-after-<?php print $rand; ?>">
  <style scoped="scoped">.twentytwenty-overlay{display: none !important; } </style>
  <img src="<?php print $before; ?>" />
  <img src="<?php print $after; ?>" />
</div>