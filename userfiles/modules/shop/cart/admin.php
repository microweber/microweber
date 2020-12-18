<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-shop-cart-settings">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Checkout link enabled"); ?> ?</label>

                    <?php $checkout_link_enanbled = get_option('data-checkout-link-enabled', $params['id']); ?>
                    <select name="data-checkout-link-enabled" class="mw-ui-field mw_option_field mw-full-width">
                        <option value="y" <?php if (('n' != strval($checkout_link_enanbled))): ?>  selected="selected"  <?php endif; ?>><?php _e("Yes"); ?></option>
                        <option value="n" <?php if (('n' == strval($checkout_link_enanbled))): ?>  selected="selected"  <?php endif; ?>><?php _e("No"); ?></option>
                    </select>
                </div>

                <div class="mw-ui-field-holder">

                    <label class="mw-ui-label"><?php _e("Use Checkout Page From"); ?></label>
                    <?php $selected_page = get_option('data-checkout-page', $params['id']); ?>

                    <select name="data-checkout-page" class="mw-ui-field mw-full-width mw_option_field">
                        <option value="default" <?php if ((0 == intval($selected_page)) or ('default' == strval($selected_page))): ?>   selected="selected"  <?php endif; ?>><?php _e("Default"); ?></option>
                        <?php
                        $pt_opts = array();
                        $pt_opts['link'] = "{title}";
                        $pt_opts['list_tag'] = " ";
                        $pt_opts['is_shop'] = "y";
                        $pt_opts['list_item_tag'] = "option";
                        $pt_opts['active_ids'] = $selected_page;
                        $pt_opts['active_code_tag'] = '   selected="selected"  ';
                        pages_tree($pt_opts);
                        ?>
                    </select>
                </div>

            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>