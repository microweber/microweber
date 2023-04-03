<div class="mw-le-layout-block">

    <div class="mw-le-layout-background">
        <!-- Background goes here -->

    </div>


    <div class="mw-le-layout-background-overlay">
        <!-- Overlay goes here -->
    </div>

    <div class="mw-le-layout-block-content">
        <div class="mw-le-spacer" id="spacer-layout-content-skin-1-<?php print $params['id'] ?>-top">

        </div>
        <div class="mw-le-layout-block-content-wrapper">


            <?php

            if ($template_file != false and is_file($template_file)) {

                include($template_file);
            }

            ?>

        </div>
        <div class="mw-le-spacer" id="spacer-layout-content-skin-1-<?php print $params['id'] ?>-bottom">

        </div>
    </div>
</div>
