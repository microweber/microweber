<?php

/*

type: layout

name: Styled

description: Default

*/
?>

<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>css/styled.css"/>
<script>mw.require("<?php print $config['url_to_module'] ?>js/styled.js", true); </script>

<div class="faq-holder styled">
    <div class="row">
        <div class="col-xs-12 edit" rel="module-<?php print $params['id']; ?>" field="title-content">
            <h3>Here’s a few answers to our most common questions</h3>
        </div>
    </div>

    <div class="row">
        <?php
        $count = 0;
        foreach ($data as $slide) {
            $count++;
            ?>

            <?php if ($count == 1) {
                $status = '';
            } else {
                $status = 'closed';
            }
            ?>
            <div class="col-xs-12 item <?php print $status; ?>">
                <div class="decorate">
                    <span class="quest-icon"></span>
                </div>
                <div class="content">
                    <h4><?php print $slide['question']; ?></h4>
                    <div><p><?php print $slide['answer']; ?></p></div>
                </div>
            </div>

        <?php } ?>
    </div>
</div>
 
