<?php

/*

type: layout

name: Default

description: Default

*/
?>

<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>css/default.css"/>

<div class="faq-holder default">
    <div class="row">
        <div class="col-xs-12">
            <h3>Here’s a few answers to our most common questions</h3>
        </div>
    </div>

    <div class="row">
        <?php
        $count = 0;
        foreach ($data as $slide) {
            $count++;
            ?>
            <div class="col-xs-12 col-sm-6">
                <h4><?php print $slide['question']; ?></h4>
                <div><p><?php print $slide['answer']; ?></p></div>
            </div>

        <?php } ?>
    </div>
</div>
 
