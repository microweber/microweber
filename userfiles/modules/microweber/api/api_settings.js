    if (window.mw) {
        console.log('%c !!! mw already defined !!! ', 'background: #009cff; color: #fff; font-size:16px;');
    }

    window.mw = {};

    mw.settings = {
        regions: false,
        liveEdit: false,
        debug: true,
        basic_mode: false,
        site_url: '<?php print site_url(); ?>',
        template_url: '<?php print TEMPLATE_URL; ?>',
        modules_url: '<?php print modules_url(); ?>',
        includes_url: '<?php   print( mw_includes_url());  ?>',
        upload_url: '<?php print site_url(); ?>api/upload/',
        api_url: '<?php print site_url(); ?>api/',
        libs_url: '<?php   print( mw_includes_url());  ?>api/libs/',
        api_html: '<?php print site_url(); ?>api_html/',
        editables_created: false,
        element_id: false,
        text_edit_started: false,
        sortables_created: false,
        drag_started: false,
        sorthandle_hover: false,
        resize_started: false,
        sorthandle_click: false,
        row_id: false,

        edit_area_placeholder: '<div class="empty-element-edit-area empty-element ui-state-highlight ui-sortable-placeholder"><span><?php _e("Please drag items here"); ?></span></div>',
        empty_column_placeholder: '<div id="_ID_" class="empty-element empty-element-column"><?php _e("Please drag items here"); ?></div>',
        handles: {
            item: "<div title='<?php _ejs("Click to select this item"); ?>.' class='mw_master_handle' id='items_handle'></div>"
        },
        sorthandle_delete_confirmation_text: "<?php _ejs("Are you sure you want to delete this element"); ?>?"
    }


<?php

print $jsRoutes;



    ?>

