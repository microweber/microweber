<?php

/*

type: layout

name: Default

description: Default

*/
?>

<script>
    mw.lib.require('bootstrap3ns');

</script>

<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>css/default.css"/>
<div class="bootstrap3ns">
    <div class="pricing-table-module">
        <div class="row">
            <?php if ($columns == 4) {
                $class = 'col-md-3 col-sm-6 col-xs-12';
            } else if ($columns == 3) {
                $class = 'col-md-offset-1 col-md-3 col-sm-4 col-xs-12';
            } else if ($columns == 2) {
                $class = 'col-md-offset-2 col-md-3 col-sm-6 col-xs-12';
            } else {
                $class = 'col-md-3 col-sm-6 col-xs-12';
            }
            ?>

            <?php for ($i = 1; $i <= $columns; $i++): ?>

                <div class="<?php print $class; ?> float-shadow">
                    <?php if ($feature == $i): ?>
                        <div class="recommended"><div class="edit" rel="module-<?php print $params['id']; ?>" field="label-content-<?php print $i; ?>"><span class="fa fa-refresh"
                                                                                                                                                                aria-hidden="true"></span>
                                RECOMMENDED</div></div>
                    <?php endif; ?>

                    <div class="price_table_container">
                        <div class="price_table_heading edit" rel="module-<?php print $params['id']; ?>" field="title-content-<?php print $i; ?>">Basic <?php print $i; ?></div>
                        <div class="price_table_body">
                            <div class="price_table_row cost <?php print $styleColor; ?> edit" rel="module-<?php print $params['id']; ?>" field="price-content-<?php print $i; ?>"><strong>$
                                    2<?php print $i; ?></strong><span>/MONTH</span>
                            </div>
                            <div class="edit" rel="module-<?php print $params['id']; ?>" field="table-content-<?php print $i; ?>">
                                <div class="price_table_row">10 Websites</div>
                                <div class="price_table_row">100 GB Storage</div>
                                <div class="price_table_row">100 GB Bandwidth</div>
                                <div class="price_table_row">50 Email Addresses</div>
                                <div class="price_table_row">Free Backup</div>
                                <div class="price_table_row">Full Time Support</div>
                            </div>
                        </div>
                        <module type="btn" button_style="<?php print $buttonColor; ?>" template="bootstrap" id="dbtn_<?php print $params['id'] . $i; ?>"/>
                    </div>
                </div>

            <?php endfor; ?>
        </div>

    </div>
</div>

<?php if (is_admin()): ?>
    <?php print notif(_e('Click here to edit the pricing table', true)); ?>
<?php endif; ?>
