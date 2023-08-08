<?php must_have_access(); ?>

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

    mw.on.hashParam('orderstype', function (pval) {
        mw.$("#cartsnav a").removeClass('active');
        mw.$("#cartsnav a[href='#orderstype=" + pval + "']").addClass('active');
        if (pval == 'carts') {
            mw.$('.mw-admin-order-sort-carts').show();
            mw.$('.mw-admin-order-sort-completed').hide();
        } else {
            mw.$('.mw-admin-order-sort-carts').hide();
            mw.$('.mw-admin-order-sort-completed').show();
        }

        mw.$('#mw-admin-manage-orders-list').attr('order-type', pval);
        mw.$('#mw-admin-manage-orders-list').removeAttr('keyword');
        mw.$('#mw-admin-manage-orders-list').removeAttr('order');
        mw.reload_module("#mw-admin-manage-orders-list", function () {
            mw.responsive.table('#shop-orders', {
                breakPoints: responsivetableOrder
            })
        });
    });

    ordersSort = function (obj) {
        var group = mw.tools.firstParentWithClass(obj.el, 'js-table-sorting');
        var table = document.getElementById(obj.id);
        var parent_mod = mw.tools.firstParentWithClass(table, 'module');
        var others = group.querySelectorAll('.js-sort-btn'), i = 0, len = others.length;

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

        var jQueryEl = $(obj.el);

        if (state === '0') {
            tosend.state = 'ASC';
//            obj.el.className = 'btn btn-link active ASC';
            obj.el.setAttribute('data-state', 'ASC');

            jQueryEl.find('i').removeClass('mdi-chevron-down');
            jQueryEl.find('i').addClass('mdi-chevron-up');
        }
        else if (state === 'ASC') {
            tosend.state = 'DESC';
//            obj.el.className = 'btn btn-link active DESC';
            obj.el.setAttribute('data-state', 'DESC');

            jQueryEl.find('i').removeClass('mdi-chevron-up');
            jQueryEl.find('i').addClass('mdi-chevron-down');
        }
        else if (state === 'DESC') {
            tosend.state = 'ASC';
//            obj.el.className = 'btn btn-link active ASC';
            obj.el.setAttribute('data-state', 'ASC');

            jQueryEl.find('i').removeClass('mdi-chevron-down');
            jQueryEl.find('i').addClass('mdi-chevron-up');
        }
        else {
            tosend.state = 'ASC';
//            obj.el.className = 'btn btn-link active ASC';
            obj.el.setAttribute('data-state', 'ASC');

            jQueryEl.find('i').removeClass('mdi-chevron-down');
            jQueryEl.find('i').addClass('mdi-chevron-up');
        }

        if (parent_mod !== undefined) {
            parent_mod.setAttribute('data-order', tosend.type + ' ' + tosend.state);
            mw.reload_module(parent_mod);
        }
    }

    mw.on.hashParam('search', function (pval) {
        var field = document.getElementById('orders-search-field');
        $(field).addClass('loading')

        if (!field.focused) {
            field.value = pval;
        }

        if (pval != '') {
            $('#mw-admin-manage-orders-list').attr('keyword', pval);
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
    //    function mw_admin_add_order_popup(ord_id) {
    //
    //        if (!!ord_id) {
    //            var modalTitle = '<?php //_e('Edit order'); ?>//';
    //        } else {
    //            var modalTitle = '<?php //_e('Add order'); ?>//';
    //        }
    //
    //

    //
    //        var params = {}
    //        params.order_id = ord_id;
    //        mw.load_module('shop/orders/admin/add_order', '#mw_admin_edit_order_item_module', null, params);
    //    }

</script>

<?php $is_orders = get_orders('order_completed=1&count=1'); ?>
<?php $latest_orders = get_orders('order_completed=1&count=1&order_status=pending'); ?>

<div id="manage-orders-menus">

    <div class="card ">
        <div class="card-header">
            <h5 class="card-title"><i class="mdi mdi-shopping text-primary mr-3"></i> <strong><?php _e("List of orders"); ?></strong>
                <a href="javascript:mw_admin_add_order_popup()" class="btn btn-sm btn-outline-success ml-2"><?php _e('Add new order'); ?></a>
            </h5>

            <div class="js-hide-when-no-items">
                <div class="js-search-by d-inline-block">
                    <div class="js-search-by-keywords">
                        <div class="form-inline">
                            <div class="input-group mb-0 prepend-transparent mx-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                                </div>

                                <input type="text" class="form-control form-control-sm" style="width: 100px;" id="orders-search-field" placeholder="<?php _e("Search"); ?>"/>
                            </div>

                            <button type="button" class="btn btn-primary btn-sm btn-icon" onclick="mw.url.windowHashParam('search',$(this).prev().find('input').val())"><i class="mdi mdi-magnify"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body  pt-3 pb-0">
            <div class="manage-toobar d-flex justify-content-between align-items-center">
                <?php if ($is_orders != 0) { ?>
                    <div id="cartsnav">
                        <a href="#orderstype=completed" class="btn btn-link btn-sm px-0   active"><?php _e("Completed orders"); ?></a>
                        <a href="#orderstype=carts" class="btn btn-link btn-sm text-muted"><?php _e("Abandoned carts"); ?></a>
                    </div>

                    <div class="js-table-sorting text-end text-right my-1 d-flex justify-content-center justify-content-sm-end align-items-center">
                        <small><?php _e("Sort By"); ?>: &nbsp;</small>

                        <div class="d-inline-block mx-1">
                            <button type="button" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" data-sort-type="created_at" onclick="ordersSort({id:'shop-orders', el:this});">
                                <?php _e("Date"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                            </button>
                        </div>
                        <div class="d-inline-block mx-1">
                            <button type="button" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" data-sort-type="order_status" onclick="ordersSort({id:'shop-orders', el:this});">
                                <?php _e("Status"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                            </button>
                        </div>
                        <div class="d-inline-block mx-1">
                            <button type="button" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" data-sort-type="amount" onclick="ordersSort({id:'shop-orders', el:this});">
                                <?php _e("Amount"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                            </button>
                        </div>
                        <!--<div class="d-inline-block mx-1">
                        <button type="button" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" data-sort-type="first_name" onclick="ordersSort({id:'shop-orders', el:this});">
                            <?php /*_e("Name"); */ ?> <i class="mdi mdi-chevron-down text-muted"></i>
                        </button>
                    </div>-->
                    </div>
                <?php } ?>
            </div>

            <module type="shop/orders/admin" id="mw-admin-manage-orders-list"/>
        </div>
    </div>
</div>
