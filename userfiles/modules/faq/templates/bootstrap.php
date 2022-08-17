<?php

/*

type: layout

name: Bootstrap

description: Default

*/
?>

<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>css/bootstrap.css"/>
<script>
    mw.lib.require('bootstrap3ns');
</script>

<div>
    <div class="faq-holder bootstrap">
        <div class="row">
            <div class="col-xs-12 edit" rel="module-<?php print $params['id']; ?>" field="title-content">
                <h3><?php _e("Here’s a few answers to our most common questions"); ?></h3>
            </div>
        </div>

        <div class="row">
            <div class="panel-group" id="faq-accordion">
                <?php
                $count = 0;
                foreach ($data as $slide) {
                    $count++;
                    ?>

                    <?php if ($count == 1) {
                        $status = '';
                        $status_in = 'in';
                    } else {
                        $status = 'collapsed';
                        $status_in = '';
                    }
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle <?php print $status; ?>" data-bs-toggle="collapse" data-parent="#faq-accordion"
                                   href="#collapse-<?php print $count; ?>"><?php print $slide['question']; ?></a>
                            </h4>
                        </div>
                        <div id="collapse-<?php print $count; ?>" class="panel-collapse collapse <?php print $status_in; ?>">
                            <div class="panel-body"><?php print $slide['answer']; ?></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

