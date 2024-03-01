<?php

/*

  type: layout

  name: Default

  description: Default skin


*/

?>

<?php $randId = md5($params['id']); ?>

<style>
    #js-element-<?php echo $randId; ?> {
        font-size: <?php echo $fontSize; ?>px;
    }
</style>

<!-- Element to contain animated typing -->
<span id="js-element-<?php echo $randId;?>"></span>

<!-- Setup and start animation! -->
<script>
    mw.lib.require("typed");

    $(document).ready(function () {
        new Typed('#js-element-<?php echo $randId;?>', {
            strings: <?php echo $textsJsonArray; ?>,
            typeSpeed: <?php echo $typeSpeed; ?>,
            backSpeed: <?php echo $backSpeed; ?>,
            shuffle: <?php if ($shuffle){ echo 'true'; } else { echo 'false'; } ?>,
            loop: <?php if ($loop){ echo 'true'; } else { echo 'false'; } ?>
        });
    });
</script>
