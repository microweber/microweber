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

        function clean (str) {
            const parser = document.createElement('div');
            parser.innerHTML = str;
            const blocks = 'div,p,h1,h2,h3,h4,h5,h6,main,article,header,footer,aside';
            let curr = parser.querySelector(blocks);
            while (curr) {
                while (curr.firstChild) {
                    curr.parentNode.insertBefore(curr.firstChild, curr);
                }
                curr.remove()
                curr = parser.querySelector(blocks);
            }
            return parser.innerHTML;
        }

        let strings = <?php echo $textsJsonArray; ?>.map(clean);

        new Typed('#js-element-<?php echo $randId;?>', {
            strings,
            typeSpeed: <?php echo $typeSpeed; ?>,
            backSpeed: <?php echo $backSpeed; ?>,
            shuffle: <?php if ($shuffle){ echo 'true'; } else { echo 'false'; } ?>,
            loop: <?php if ($loop){ echo 'true'; } else { echo 'false'; } ?>
        });

    });
</script>


