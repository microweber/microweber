<?php

/*

type: layout

name: Default

description: Default

*/
?>
<div class="mw-module-skills-list">
    <?php
    $file = get_option('file', $params['id']);
    if (!!$file) {
        $skills = json_decode($file, true);

        foreach ($skills as $skill) { ?>
            <?php
            $color = 'progress-bar-' . $skill['style'];
            $percent = 'progress-bar-' . $skill['style'];
            if (isset($skill['percent'])) {
                $percent = $skill['percent'];
            } else {
                $percent = 50;
            }
            ?>
            <div class="barchart barchart-2" data-value="<?php print $percent; ?>">
                <div class="barchart__description">
                    <span class="h6"><?php print $skill['skill']; ?></span>
                </div>
                <div class="barchart__bar">
                    <div class="barchart__progress"></div>
                </div>
            </div>
        <?php }
    } else {
        print lnotif('Click to insert skills');
    }
    ?>
</div>