<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="row">

    <?php
    $count = 0;
    $default_img = template_url() . 'assets/img/avatar.jpg';
    if (isset($data) AND !empty($data)): ?>
        <?php foreach ($data as $slide):
            $count++;
            ?>
            <div class="col-sm-4 col-xs-6">
                <div class="hover-element member member-2" data-title-position="center,center">

                    <div class="hover-element__initial">
                        <img alt="<?php print array_get($slide, 'name'); ?>" src="<?php print thumbnail($slide['file'], 577, 700, true); ?>"/>
                    </div>
                    <div class="hover-element__reveal" data-overlay="9">
                        <div class="boxed">
                            <h6><?php print array_get($slide, 'role'); ?></h6>
                            <h5><?php print array_get($slide, 'name'); ?></h5>
                        </div>

                        <module type="social_links" template="skin-1" id="team_card_<?php print $params['id']; ?>_<?php print md5(array_get($slide, 'name')); ?>" class="pos-absolute pos-bottom"
                                style="width: 100%;"/>

                    </div>
                </div>
                <!--end hover element-->
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-sm-4 col-xs-6">
            <div class="hover-element member member-2" data-title-position="center,center">
                <div class="hover-element__initial">
                    <img alt="John Doe" src="<?php print thumbnail($default_img, 577, 700, true); ?>"/>
                </div>
                <div class="hover-element__reveal" data-overlay="9">
                    <div class="boxed">
                        <h6>Developer</h6>
                        <h5>John Doe</h5>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
 
