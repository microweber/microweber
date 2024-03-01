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
    mw.require("//unpkg.com/typed.js@2.1.0/dist/typed.umd.js");

    $(document).ready(function () {
        new Typed('#js-element-<?php echo $randId;?>', {
            strings: <?php echo $textsJsonArray; ?>,
            typeSpeed: <?php echo $typeSpeed; ?>,
            backSpeed: <?php echo $backSpeed; ?>,
            loop: <?php if ($loop): ?> true <?php else: ?> false <?php endif; ?>
        });
    });
</script>
