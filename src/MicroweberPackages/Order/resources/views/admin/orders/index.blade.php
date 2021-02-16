<div id="manage-orders-menus">
    <div class="card style-1">
        <div class="card-header">
            <h5><i class="mdi mdi-shopping text-primary mr-3"></i> <strong><?php _e("List of orders"); ?></strong>
                <button onclick="mw_admin_add_order_popup()" class="btn btn-sm btn-outline-success ml-2"><?php print _e('Add new order'); ?></button>
            </h5>

            <div class="js-hide-when-no-items">
                <div class="js-search-by d-inline-block">
                    <div class="js-search-by-keywords">
                        <form method="get">
                        <div class="form-inline">
                            <div class="input-group mb-0 prepend-transparent mx-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span>
                                </div>
                                <input type="hidden" name="searchInFields" value="id" />
                                <input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control form-control-sm" style="width: 220px;" placeholder="<?php _e("Search"); ?>"/>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-magnify"></i></button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-3 pb-0">
            <form method="get">
            <div class="manage-toobar d-flex justify-content-between align-items-center">
                <?php if (count($orders) != 0) { ?>
                <div id="cartsnav">
                    <a href="?orderstype=completed" class="btn btn-link btn-sm px-0 text-dark active"><?php _e("Completed orders"); ?></a>
                    <a href="?orderstype=carts" class="btn btn-link btn-sm text-muted"><?php _e("Abandoned carts"); ?></a>
                </div>

                <div class="js-table-sorting text-right my-1 d-flex justify-content-center justify-content-sm-end align-items-center">
                    <small><?php _e("Sort By"); ?>: &nbsp;</small>

                    <div class="d-inline-block mx-1">
                        <button type="submit" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" name="sort" value="created_at">
                            <?php _e("Date"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                        </button>
                    </div>
                    <div class="d-inline-block mx-1">
                        <button type="submit" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" name="sort" value="order_status">
                            <?php _e("Status"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                        </button>
                    </div>
                    <div class="d-inline-block mx-1">
                        <button type="submit" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" name="sort" value="amount">
                            <?php _e("Amount"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                        </button>
                    </div>
                </div>
                <?php } ?>
            </div>
            </form>

            <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
                <?php echo $order['id']; ?>
            <?php endforeach;?>
             <?php endif; ?>


            <?php if (($filteringResults == true) && (count($orders) == 0)): ?>
            <div class="no-items-found orders">
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_results.png'); ">
                            <h4><?php _e("No orders found for this query filtering"); ?></h4>
                            <p><?php _e("Try with other filters"); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>


            <?php if (($filteringResults == false) && (count($orders) == 0)): ?>
            <div class="no-items-found orders">
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_orders.svg'); ">
                            <h4><?php _e("You don’t have any orders yet"); ?></h4>
                            <p><?php _e("Here you can track your orders"); ?></p>
                            <br/>
                            <a href="javascript:mw_admin_add_order_popup()" class="btn btn-primary btn-rounded"><?php _e("Add order"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="d-flex">
                <div class="mx-auto">
                    <?php echo $orders->links("pagination::bootstrap-4"); ?>
                </div>
            </div>

        </div>
    </div>
</div>