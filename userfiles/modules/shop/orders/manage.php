<?php only_admin_access(); ?>
<script>
    responsivetableOrder = {
        768: 4,
        500: 2,
        400: 1
    }
    mw.require('forms.js', true);


    $(mwd).ready(function () {

        mw.responsive.table('#shop-orders', {
            breakPoints: responsivetableOrder
        })
    });


    mw.on.hashParam('orderstype', function () {
        mw.$("#cartsnav a").removeClass('active');
        mw.$("#cartsnav a[href='#orderstype=" + this + "']").addClass('active');
        if (this == 'carts') {
            mw.$('.mw-admin-order-sort-carts').show();
            mw.$('.mw-admin-order-sort-completed').hide();
        } else {
            mw.$('.mw-admin-order-sort-carts').hide();
            mw.$('.mw-admin-order-sort-completed').show();
        }
        mw.$('#mw-admin-manage-orders-list').attr('order-type', this);
        mw.$('#mw-admin-manage-orders-list').removeAttr('keyword');
        mw.$('#mw-admin-manage-orders-list').removeAttr('order');
        mw.reload_module("#mw-admin-manage-orders-list", function () {
            mw.responsive.table('#shop-orders', {
                breakPoints: responsivetableOrder
            })
        });
    });


    ordersSort = function (obj) {
        var group = mw.tools.firstParentWithClass(obj.el, 'mw-table-sorting');

        var table = mwd.getElementById(obj.id);

        var parent_mod = mw.tools.firstParentWithClass(table, 'module');

        var others = group.querySelectorAll('.mw-ui-btn'), i = 0, len = others.length;
        for (; i < len; i++) {
            var curr = others[i];
            if (curr !== obj.el) {
                $(curr).removeClass('ASC DESC active');
            }
        }
        obj.el.attributes['data-state'] === undefined ? obj.el.setAttribute('data-state', 0) : '';
        var state = obj.el.attributes['data-state'].nodeValue;
        var tosend = {}
        tosend.type = obj.el.attributes['data-sort-type'].nodeValue;
        if (state === '0') {
            tosend.state = 'ASC';
            obj.el.className = 'mw-ui-btn mw-ui-btn-medium active ASC';
            obj.el.setAttribute('data-state', 'ASC');
        }
        else if (state === 'ASC') {
            tosend.state = 'DESC';
            obj.el.className = 'mw-ui-btn mw-ui-btn-medium active DESC';
            obj.el.setAttribute('data-state', 'DESC');
        }
        else if (state === 'DESC') {
            tosend.state = 'ASC';
            obj.el.className = 'mw-ui-btn mw-ui-btn-medium active ASC';
            obj.el.setAttribute('data-state', 'ASC');
        }
        else {
            tosend.state = 'ASC';
            obj.el.className = 'mw-ui-btn mw-ui-btn-medium active ASC';
            obj.el.setAttribute('data-state', 'ASC');
        }

        if (parent_mod !== undefined) {
            parent_mod.setAttribute('data-order', tosend.type + ' ' + tosend.state);
            mw.reload_module(parent_mod);
        }
    }

    mw.on.hashParam('search', function () {


        var field = mwd.getElementById('orders-search-field');
        $(field).addClass('loading')

        if (!field.focused) {
            field.value = this;
        }

        if (this != '') {
            $('#mw-admin-manage-orders-list').attr('keyword', this);
        } else {
            $('#mw-admin-manage-orders-list').removeAttr('keyword');
        }

        $('#mw-admin-manage-orders-list').removeAttr('export_to_excel');


        mw.reload_module('#mw-admin-manage-orders-list', function () {
            mw.$(field).removeClass('loading');

            mw.responsive.table('#shop-orders', {
                breakPoints: responsivetableOrder
            })
        });


    });
    function mw_admin_add_order_popup(ord_id) {

        if (!!ord_id) {
            var modalTitle = '<?php _e('Edit order'); ?>';
        } else {
            var modalTitle = '<?php _e('Add order'); ?>';
        }


        mw_admin_edit_order_item_popup_modal_opened = mw.modal({
            content: '<div id="mw_admin_edit_order_item_module"></div>',
            title: modalTitle,
            id: 'mw_admin_edit_order_item_popup_modal'
        });

        var params = {}
        params.order_id = ord_id;
        mw.load_module('shop/orders/admin/add_order', '#mw_admin_edit_order_item_module', null, params);
    }

</script>
<?php $is_orders = get_orders('order_completed=1&count=1'); ?>

<?php $latest_orders = get_orders('order_completed=1&count=1&order_status=pending'); ?>


<div class="mw-table-sorting-controller">
    <div class="section-header">
        <div class="mw-ui-row valign" style="margin-bottom: 20px;">
            <div class="mw-ui-col">
                <div class="mw-ui-row" style=" width: auto">
                    <div class="mw-ui-col">
                        <div class="mw-ui-col-container">
                            <a class="ordersnum" href="#orderstype=completed"><?php print $latest_orders; ?></a>
                        </div>
                    </div>
                    <div class="mw-ui-col">
                        <div class="mw-ui-col-container">
                            <h2>
                                <span class="mai-shop"></span>


                                <?php if ($latest_orders > 1) { ?>
                                    <?php _e("New orders"); ?>
                                <?php }
                                if ($latest_orders == 1) { ?>
                                    <?php _e("New order"); ?>
                                <?php }
                                if ($latest_orders == 0) { ?>
                                    <?php _e("No new orders"); ?>
                                <?php } ?>

                            </h2>
                            <a href="javascript:mw_admin_add_order_popup()">+</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mw-ui-col" style="width: 80%">
                <div class="pull-right relative">

                    <div class="top-search">
                        <input type="text" class="mw-ui-searchfield active pull-right" id="orders-search-field" placeholder="<?php _e("Search in orders"); ?>" />
                        <span class="top-form-submit" onclick="mw.url.windowHashParam('search', $(this).prev().val());"><span class="mw-icon-search"></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="admin-side-content">
    <div id="manage-orders-menus">


        <?php if ($is_orders != 0) { ?>
            <div class="mw-table-sorting mw-admin-order-sort-completed pull-right" style="margin-left: 20px;">
                <div class="mw-ui-btn-nav unselectable pull-right" style="margin-left: 10px;">
                <span class="mw-ui-btn mw-ui-btn-medium" data-sort-type="created_at" onclick="ordersSort({id:'shop-orders', el:this});">
            		<?php _e("Date"); ?>
            	</span>
                    <span class="mw-ui-btn mw-ui-btn-medium" data-sort-type="order_status" onclick="ordersSort({id:'shop-orders', el:this});">
            		<?php _e("Status"); ?>
            	</span>
                    <span class="mw-ui-btn mw-ui-btn-medium" data-sort-type="amount" onclick="ordersSort({id:'shop-orders', el:this});">
            		<?php _e("Amount"); ?>
            	</span>
                    <span class="mw-ui-btn mw-ui-btn-medium" data-sort-type="first_name" onclick="ordersSort({id:'shop-orders', el:this});">
            		<?php _e("Name"); ?>
            	</span>


                </div>
                <label class="pull-right" style="margin-top: 10px;"><?php _e("Sort By"); ?>:</label>
            </div>
        <?php } ?>


        <div class="mw-ui-btn-nav unselectable pull-right" id="cartsnav">
            <a href="#orderstype=completed" class="mw-ui-btn mw-ui-btn-medium active"><?php _e("Completed orders"); ?></a>
            <a href="#orderstype=carts" class="mw-ui-btn mw-ui-btn-medium"><?php _e("Abandoned carts"); ?></a>
        </div>

    </div>


    <module type="shop/orders/admin" id="mw-admin-manage-orders-list" />
</div>