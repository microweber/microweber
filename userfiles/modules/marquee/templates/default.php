<?php

/*

  type: layout

  name: Default

  description: Default skin


*/

?>

<?php $randId = md5($params['id']); ?>

<style>
    #v-marquee-<?php echo $randId; ?>{
        overflow: hidden;
        font-size: <?php echo $fontSize; ?>px;
    }
</style>

<div id="v-marquee-<?php echo $randId; ?>"><?php echo $text; ?></div>

<script type="module">
    import marquee from '<?php echo modules_url() ?>marquee/vanilla-marquee.min.js';
    new marquee( document.getElementById( 'v-marquee-<?php echo $randId; ?>' ), {
        speed:  <?php echo $animationSpeed; ?>,
    })
</script>
