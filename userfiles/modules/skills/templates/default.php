<?php

/*

type: layout

name: Default

description: Default

*/
?>
<div class="mw-module-skills-list">
    <div class="skillst">
        <script>
            //Skill
            jQuery(document).ready(function () {
                jQuery('.skillbar').each(function () {
                    jQuery(this).appear(function () {
                        jQuery(this).find('.count-bar').animate({
                            width: jQuery(this).attr('data-percent')
                        }, 3000);
                        var percent = jQuery(this).attr('data-percent');
                        jQuery(this).find('.count').html('<span>' + percent + '</span>');
                    });
                });
            });
        </script>


        <?php

        $file = get_option('file', $params['id']);
        if (!!$file) {
            $skills = json_decode($file, true);

            foreach ($skills as $skill) { ?>
            <?php var_dump($skill); ?>

                <div class="skillbar style-<?php $skill['style'] ?>" data-percent="<?php print isset($skill['percent'])?$skill['percent']:50; ?>%">
                    <div class="title head-sm">
                        <?php print $skill['skill']; ?>
                    </div>
                    <div class="count-bar">
                        <div class="count"></div>
                    </div>
                </div>

            <?php }
        } else {
            print lnotif('Click to insert skills');
        }
        ?>
    </div>
</div>