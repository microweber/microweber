<?php
$items_number = get_option('items_number', $params['id']);
$maxRowHeight = get_option('max_row_height', $params['id']);
$rowHeight = get_option('row_height', $params['id']);
if (!$maxRowHeight) {
    $maxRowHeight = 250;
}
if (!$rowHeight) {
    $rowHeight = 120;
}
if (!$items_number) {
    $items_number = 10;
}
?>
<script>
    $(mwd).ready(function () {
        $('[data-type="pictures/admin"]').on('change', function () {
              mw.reload_module_everywhere('#<?php print $params['id']; ?>')
        });
        mw.on.moduleReload('pa<?php print $params['id']; ?>', function () {
            $('[data-type="pictures/admin"]').on('change', function () {
                mw.reload_module_everywhere('#<?php print $params['id']; ?>')
            });
        });
    });
</script>


<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-navicon-round"></i> List of Images
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <div class="module-live-edit-settings module-carousel-grid-settings" style="padding: 0">
                <module
                        type="pictures/admin"
                        rel_id="<?php print $params['id']; ?>"
                        id="pa<?php print $params['id']; ?>" />
            </div>
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-carousel-grid-settings">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Items per slide</label>
                    <input type="number" class="mw-ui-field mw-full-width mw_option_field" name="items_number" value="<?php print $items_number; ?>"/>
                </div>
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Minimum Row height</label>
                    <input type="number" class="mw-ui-field mw-full-width mw_option_field" name="row_height" placeholder="120" value="<?php print $rowHeight; ?>"/>
                </div>
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Max Row height</label>
                    <input type="number" class="mw-ui-field mw-full-width mw_option_field" name="max_row_height" placeholder="250" value="<?php print $maxRowHeight; ?>"/>
                </div>
            </div>
            <!-- Settings Content - End -->
        </div>
    </div>
</div>