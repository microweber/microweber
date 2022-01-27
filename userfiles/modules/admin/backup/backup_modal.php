<template id="export-template">

    <div class="mw-construct-itd mb-4">
        <div class="mw-construct-itd-icon">
            <span class="mw-micon-Data-Download"></span>
        </div>
        <div class="mw-construct-itd-content">
            <h3><?php _e("Create full backup of your site"); ?></h3>
            <p><?php _e("Use the button to export full backup of your website whit all data"); ?>.</p>
        </div>
    </div>

    <div class="mb-4">
        <button type="button" class="btn btn-primary" onclick="mw.backup.start()">
            <?php _e("Start backup process"); ?>
        </button>
    </div>

    <div class="js-export-log"></div>

</template>
