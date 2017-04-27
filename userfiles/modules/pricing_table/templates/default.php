<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="pricing-table-module">
    <div class="row">
        <div class=" plans-row">

            <?php for ($i = 1; $i <= $columns; $i++): ?>
                <div class="col-xs-12 col-sm-6 col-md-3 item enabled-true <?php if ($feature == $i) { echo 'active'; } ?>">
                    <?php if ($feature == $i): ?>
                        <div class="fav-plan"></div>
                    <?php endif; ?>

                    <div class="plan-container">
                        <h3><span class="price-plan-name edit" rel="module-<?php print $params['id']; ?>" field="title-content">Starter</span></h3>
                        <p class="price edit" rel="module-<?php print $params['id']; ?>" field="price-content">$9/mo</p>

                        <div class="plans-plan-features edit" rel="module-<?php print $params['id']; ?>" field="table-content">
                            <ul>
                                <li><span class="check"></span><span>Full access</span></li>
                                <li><span class="check"></span><span>Documentation</span></li>
                                <li><span class="not"></span><span>Customer support</span></li>
                                <li><span class="not"></span><span>Free updates</span></li>
                                <li><span class="not"></span><span>Unlimited domains</span></li>
                            </ul>
                        </div>

                        <div class="text-center">
                            <module type="btn"/>
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
