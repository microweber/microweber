<div style="padding:30px;background:#a88d0e;color:#fff">
    Here is an example ui of Microweber.
    Click to edit the module to see ui.
</div>

<div>
    <?php

    foreach(app()->module_manager->get() as $item) {
        if ($item['ui'] !== 1) {
            continue;
        }
       // dump($item['module']);
        // echo '<module type="'.$item['module'].'" />';
    }

    ?>
</div>
