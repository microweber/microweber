<?php

/*

  type: layout

  name: Default

  description: Default skin


*/

?>

<?php $randId = md5($params['id']); ?>

<style>
    .wrapper-<?php echo $randId; ?> {
        width: 100%;
        overflow: hidden;
    }

    .marquee-<?php echo $randId; ?> {
        width: 100%;
        font-size: <?php echo $fontSize; ?>px;
        white-space: nowrap;
        overflow: hidden;
        display: inline-block;
        animation: marquee-<?php echo $randId; ?> <?php echo ($animationSpeed); ?>s linear infinite;
    }

    .marquee-<?php echo $randId; ?> div {
        display: inline-block;
    }

    @keyframes marquee-<?php echo $randId; ?> {
        0% {
            transform: translate(50%, 0);
        }

        100% {
            transform: translate(-50%, 0);
        }
    }

</style>

<div class="wrapper-<?php echo $randId; ?>">
    <div class="marquee-<?php echo $randId; ?>">
        <?php echo $text; ?>
    </div>
</div>
