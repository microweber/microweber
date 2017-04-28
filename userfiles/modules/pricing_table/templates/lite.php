<?php

/*

type: layout

name: Lite

description: Default

*/
?>

<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>css/lite.css"/>

<div class="pricing-table-module lite">
    <div class="row">
        <div class=" plans-row">

            <?php for ($i = 1; $i <= $columns; $i++): ?>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="item enabled-true <?php if ($feature == $i) {
                        echo 'active';
                    } ?>">
                        <div class="item-header">
                            <div class="col-xs-6">
                                <p class="level">Level 1</p>
                                <p class="name">Standard</p>
                            </div>
                            <div class="col-xs-6"><p class="price-wrapper"><span class="price">$3</span><span class="per"> / month</span></p></div>
                        </div>
                        <?php if ($feature == $i): ?>
                            <div class="fav-plan"></div>
                        <?php endif; ?>

                        <div class="plan-container">
                            <div class="plans-plan-features edit" rel="module-<?php print $params['id']; ?>" field="table-content-<?php print $i; ?>">
                                <ul>
                                    <li><span>Full access</span></li>
                                    <li><span>Documentation</span></li>
                                    <li><span>Customer support</span></li>
                                    <li><span>Free updates</span></li>
                                    <li><span>Unlimited domains</span></li>
                                </ul>
                            </div>

                            <div class="text-center">
                                <module type="btn"/>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>

        </div>
    </div>
</div>

<?php if (is_admin()): ?>
    <?php print notif(_e('Click here to edit the pricing table', true)); ?>
<?php endif; ?>
