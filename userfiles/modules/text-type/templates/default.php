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

<!-- Load library from the CDN -->
<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

<!-- Setup and start animation! -->
<script>
    var typed = new Typed('#js-element-<?php echo $randId;?>', {
        strings: ['<i>First</i> sentence.', '&amp; a second sentence.'],
        typeSpeed: <?php echo $animationSpeed; ?>,
        typeSpeed: 40,
        backSpeed: 0,
        loop: true
    });
</script>
