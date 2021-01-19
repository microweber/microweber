<?php

/*

type: layout

name: Default

description: Content views count

*/

?>


<div class="mw-stats-content-views-count" >

    <?php if ($views): ?>
        <?php print $views; ?>
    <?php else: ?>
        0
    <?php endif; ?>



</div>
