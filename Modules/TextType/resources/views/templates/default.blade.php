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
        font-size: <?php echo isset($fontSize) ? $fontSize : 24; ?>px;
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

        let strings = <?php echo json_encode(explode(PHP_EOL, isset($text) ? $text : '')); ?>.map(clean);

        new Typed('#js-element-<?php echo $randId;?>', {
            strings,
            typeSpeed: <?php echo isset($animationSpeed) ? $animationSpeed : 100; ?>,
            backSpeed: <?php echo isset($backSpeed) ? $backSpeed : 50; ?>,
            shuffle: <?php echo isset($shuffle) && $shuffle ? 'true' : 'false'; ?>,
            loop: <?php echo isset($loop) && $loop ? 'true' : 'false'; ?>
        });

    });
</script>

