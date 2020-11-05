(function () {

    if (window.mw) {
        console.log('%c !!! mw already defined !!! ', 'background: #009cff; color: #fff; font-size:16px;');
        return;
    }

    var mw = {};

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
            item: "<div title='<?php _e("Click to select this item"); ?>.' class='mw_master_handle' id='items_handle'></div>"
        },
        sorthandle_delete_confirmation_text: "<?php _e("Are you sure you want to delete this element"); ?>?"
    }



    mw.lang = function (key) {
        var camel = key.trim().replace(/(?:^\w|[A-Z]|\b\w)/g, function (letter, index) {
            return index == 0 ? letter.toLowerCase() : letter.toUpperCase();
        }).replace(/\s+/g, '');
        if (mw._lang[camel]) {
            return mw._lang[camel];
        }
        else {
            // console.warn('"' + key + '" is not present.');
            return key;
        }
    };
    mw.msg = mw._lang = {
        uniqueVisitors: '<?php _e("Unique visitors"); ?>',
        allViews: '<?php _e("All views"); ?>',
        date: '<?php _e("Date"); ?>',
        weekDays: {
            regular: [
                '<?php _e("Sunday"); ?>',
                '<?php _e("Monday"); ?>',
                '<?php _e("Tuesday"); ?>',
                '<?php _e("Wednesday"); ?>',
                '<?php _e("Thursday"); ?>',
                '<?php _e("Friday"); ?>',
                '<?php _e("Saturday"); ?>'
            ],
            short: [
                '<?php _e("Sun"); ?>',
                '<?php _e("Mon"); ?>',
                '<?php _e("Tue"); ?>',
                '<?php _e("Wed"); ?>',
                '<?php _e("Thu"); ?>',
                '<?php _e("Fri"); ?>',
                '<?php _e("Sat"); ?>'
            ]
        },
        months: {
            regular: [
                '<?php _e("January") ?>',
                '<?php _e("February") ?>',
                '<?php _e("March") ?>',
                '<?php _e("April") ?>',
                '<?php _e("May") ?>',
                '<?php _e("June") ?>',
                '<?php _e("July") ?>',
                '<?php _e("August") ?>',
                '<?php _e("September") ?>',
                '<?php _e("October") ?>',
                '<?php _e("November") ?>',
                '<?php _e("December") ?>'
            ],
            short: [
                '<?php _e("Jan") ?>',
                '<?php _e("Feb") ?>',
                '<?php _e("Mar") ?>',
                '<?php _e("Apr") ?>',
                '<?php _e("May") ?>',
                '<?php _e("June") ?>',
                '<?php _e("July") ?>',
                '<?php _e("Aug") ?>',
                '<?php _e("Sept") ?>',
                '<?php _e("Oct") ?>',
                '<?php _e("Nov") ?>',
                '<?php _e("Dec") ?>'
            ]
        },
        ok: "<?php _e('OK');  ?>",
        category: "<?php _e('Category');  ?>",
        published: "<?php _e('Published');  ?>",
        unpublished: "<?php _e('Unpublished');  ?>",
        contentunpublished: "<?php _e("Content is unpublished"); ?>",
        contentpublished: "<?php _e("Content is published"); ?>",
        save: "<?php _e('Save');  ?>",
        saving: "<?php _e('Saving');  ?>",
        saved: "<?php _e('Saved');  ?>",
        settings: "<?php _e('Settings');  ?>",
        cancel: "<?php _e('Cancel');  ?>",
        remove: "<?php _e('Remove');  ?>",
        close: "<?php _e('Close');  ?>",
        to_delete_comment: "<?php _e('Are you sure you want to delete this comment'); ?>",
        del: "<?php _e('Are you sure you want to delete this?'); ?>",
        save_and_continue: "<?php _e('Save &amp; Continue'); ?>",
        before_leave: "<?php _e("Leave without saving"); ?>",
        session_expired: "<?php _e("Your session has expired"); ?>",
        login_to_continue: "<?php _e("Please login to continue"); ?>",
        more: "<?php _e("More"); ?>",
        templateSettingsHidden: "<?php _e("Template settings"); ?>",
        less: "<?php _e("Less"); ?>",
        product_added: "<?php _e("Your product is added to cart"); ?>",
        no_results_for: "<?php _e("No results for"); ?>",
        switch_to_modules: '<?php _e("Switch to Modules"); ?>',
        switch_to_layouts: '<?php _e("Switch to Layouts"); ?>',
        loading: '<?php _e("Loading"); ?>',
        edit: '<?php _e("Edit"); ?>',
        change: '<?php _e("Change"); ?>',
        submit: '<?php _e("Submit"); ?>',
        settingsSaved: '<?php _e("Settings are saved"); ?>',
        addImage: '<?php _e("Add new image"); ?>'
    };


    if (!window.mw) {
        window.mw = mw;
    }
})();


