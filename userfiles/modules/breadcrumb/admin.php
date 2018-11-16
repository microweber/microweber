<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-breadcrumb-settings">
                <?php $selected_start_depth = get_option('data-start-from', $params['id']); ?>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Root level"); ?></label>
                    <select name="data-start-from" class="mw-ui-field mw_option_field mw-full-width">
                        <option value='' <?php if ('' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Default"); ?></option>
                        <option value='page' <?php if ('page' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Page"); ?></option>
                        <?php ?>
                        <option value='category' <?php if ('category' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Category"); ?></option>
                        <?php ?>
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