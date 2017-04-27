<?php

/*

type: layout

name: 2 Columns

description: Default

*/
?>

<div class="pricing-table-module">
    <div class="row">

        <div class=" plans-row">
            <div class="col-xs-3 item enabled-true">
                <div class="plan-container">
                    <h3><span class="price-plan-name">Starter</span></h3>
                    <p class="price">$9/mo</p>

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

            <div class="col-xs-3 item enabled-true active">
                <div class="fav-plan"></div>
                <div class="plan-container">
                    <h3><span class="price-plan-name">Basic</span></h3>
                    <p class="price">$19/mo</p>

                    <div class="plans-plan-features edit" rel="module-<?php print $params['id']; ?>" field="table-content">
                        <ul>
                            <li><span class="check"></span><span>Full access</span></li>
                            <li><span class="check"></span><span>Documentation</span></li>
                            <li><span class="check"></span><span>Customer support</span></li>
                            <li><span class="not"></span><span>Free updates</span></li>
                            <li><span class="not"></span><span>Unlimited domains</span></li>
                        </ul>
                    </div>

                    <div class="text-center">
                        <module type="btn"/>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php if (is_admin()): ?>
    <?php print notif(_e('Click here to edit the pricing table', true)); ?>
<?php endif; ?>
