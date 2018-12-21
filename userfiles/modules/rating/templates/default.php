<?php

/*

type: layout

name: Default

description: Default skin

*/

if(!isset($ratings)){
    return;
}
?>

<script type="text/javascript">
    mw.moduleJS('<?php print module_url(); ?>lib.js');
    mw.moduleJS('<?php print module_url(); ?>rating.js');

    $(document).ready(function () {
    $("#stars<?php print $params['id'] ?>").starrr()
    });
</script>

<div id="stars<?php print $params['id'] ?>" class="starrr"
    <?php if(isset($require_comment) and $require_comment) { ?> data-require-comment=true <?php } ?>
     data-rating='<?php print $ratings ?>'
     data-rel-type="<?php print $rel_type ?>"
     data-rel-id="<?php print $rel_id ?>">

</div>
