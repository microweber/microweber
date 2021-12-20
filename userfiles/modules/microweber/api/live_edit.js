mw.live_edit = mw.live_edit || {};

mw.live_edit.registry = mw.live_edit.registry || {};

mw.live_edit.hasAbilityToDropElementsInside = function(target) {
    var items = /^(span|h[1-6]|hr|ul|ol|input|table|b|em|i|a|img|textarea|br|canvas|font|strike|sub|sup|dl|button|small|select|big|abbr|body)$/i;
    if (typeof target === 'string') {
        return !items.test(target);
    }
    if(!mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['allow-drop', 'nodrop'])){
        return false;
    }
    if(mw.tools.hasAnyOfClasses(target, ['plain-text'])){
        return false;
    }
    var x = items.test(target.nodeName);
    if (x) {
        return false;
    }
    if (mw.tools.hasParentsWithClass(target, 'module')) {
        if (mw.tools.hasParentsWithClass(target, 'edit')) {
            return true;
        } else {
            return false;
        }
    } else if (mw.tools.hasClass(target, 'module')) {
        return false;
    }
    return true;
};



mw.live_edit.showSettings = function (a, opts) {

    var liveedit = opts.liveedit || false;
    var mode = opts.mode ||  'modal';

    var view = opts.view || 'admin';
    var module_type;
    if (typeof a === 'string') {
        module_type = a;
        var module_id = a;
        var mod_sel = mw.$(a + ':first');
        if (mod_sel.length > 0) {
            var attr = $(mod_sel).attr('id');
            if (typeof attr !== typeof undefined && attr !== false) {
                attr = !attr.contains("#") ? attr : attr.replace("#", '');
                module_id = attr;
            }
            var attr2 = $(mod_sel).attr('type');
            attr = $(mod_sel).attr('data-type');
            if (typeof attr !== typeof undefined && attr !== false) {
                module_type = attr;
            } else if (typeof attr2 !== typeof undefined && attr2 !== false) {
                module_type = attr2;
            }
            a = mod_sel[0]
        }
    }

    var curr = a || $("#mw_handle_module").data("curr");
    if(!curr){
        return;
    }
    if(typeof(curr) === 'undefined'){
        return;
    }
    var attributes = {};

    if (curr && curr.attributes) {
        $.each(curr.attributes, function (index, attr) {
            attributes[attr.name] = attr.value;
        });
    }

    var iframe_id_sidebar = 'js-iframe-module-settings-' + curr.id;
    var iframe_id_sidebar_wrapper_id = 'sidebar-frame-wrapper-' + iframe_id_sidebar;

    var data1 = attributes;

    module_type = null;
    if (data1['data-type'] !== undefined) {
        module_type = data1['data-type'];
        data1['data-type'] = data1['data-type'] + '/admin';
    }
    if (data1['data-module-name'] !== undefined) {
        delete(data1['data-module-name']);
    }
    if (data1['type'] !== undefined) {
        module_type = data1['type'];
        data1['type'] = data1['type'] + '/admin';
    }
    if (module_type != null && view !== undefined) {
        data1['data-type'] = data1['type'] = module_type + '/' + view;
    }
    if (typeof data1['class'] !== 'undefined') {
        delete(data1['class']);
    }
    if (typeof data1['style'] !== 'undefined') {
        delete(data1['style']);
    }
    if (typeof data1.contenteditable !== 'undefined') {
        delete(data1.contenteditable);
    }
    data1.live_edit = liveedit;
    data1.module_settings = 'true';
    if (view !== undefined) {
        data1.view = view;
    }
    else {
        data1.view = 'admin';
    }
    if (data1.from_url == undefined) {
        //data1.from_url = mw.top().win.location;
        data1.from_url = mw.parent().win.location.href;
    }
    var modal_name = 'module-settings-' + curr.id;
    if (typeof(data1.view.hash) == 'function') {
        //var modal_name = 'module-settings-' + curr.id +(data1.view.hash());
    }
    //data1.live_edit_sidebar = true;

    var src = mw.settings.site_url + "api/module?" + json2url(data1);

    if (self !== top || /*!mw.liveEditSettings.active || */ mode === 'modal') {
        //remove from sidebar
        $("#" + iframe_id_sidebar).remove();

        //close sidebar
        if(mw.liveEditSettings && mw.liveEditSettings.active){
             mw.liveEditSettings.hide();
        }
        var has = mw.$('#' + modal_name);
        if(has.length){
            var dialog = mw.dialog.get(has[0]);
            dialog.show();
            return dialog;
        }
        var nmodal = mw.dialogIframe({
            url: src,
            width: 532,
            height: 'auto',
            autoHeight:true,
            id: modal_name,
            title:'',
            className: 'mw-dialog-module-settings',
            closeButtonAction: 'remove'
        });

        nmodal.iframe.contentWindow.thismodal = nmodal;
        return nmodal;

    } else {


        if(!mw.liveEditSettings.active){
            mw.liveEditSettings.show();
        }

        if(mw.sidebarSettingsTabs){
            mw.sidebarSettingsTabs.set(2);
        }



        data1.live_edit_sidebar = true;

        var src = mw.settings.site_url + "api/module?" + json2url(data1);


        var mod_settings_iframe_html_fr = '' +
            '<div class="js-module-settings-edit-item-group-frame loading" id="' + iframe_id_sidebar_wrapper_id + '">' +
            '<iframe src="' + src + '" frameborder="0" onload="this.parentNode.classList.remove(\'loading\')">' +
            '</div>';


        var sidebar_title_box = mw.live_edit.getModuleTitleBar(module_type, curr.id);


         var mod_settings_iframe_html = '<div  id="' + iframe_id_sidebar + '" class="js-module-settings-edit-item-group">'
            + sidebar_title_box
            + mod_settings_iframe_html_fr
            + '</div>';


        if (!$("#" + iframe_id_sidebar).length) {
            $("#js-live-edit-module-settings-items").append(mod_settings_iframe_html);
        }

        if ($("#" + iframe_id_sidebar).length) {
            $('.js-module-settings-edit-item-group').hide();
            $("#" + iframe_id_sidebar).attr('data-settings-for-module', curr.id);

            $("#" + iframe_id_sidebar).show();
        }
    }

};


mw.live_edit.getModuleTitleBar = function (module_type, module_id) {

    var mod_icon = mw.live_edit.getModuleIcon(module_type);
    var mod_title = mw.live_edit.getModuleTitle(module_type);
    var mod_descr = mw.live_edit.getModuleDescription(module_type);

    var sidebar_title_box = "<div class='mw_module_settings_sidebar_title_wrapper js-module-titlebar-"+module_id+"'>" + mod_icon;
    sidebar_title_box = sidebar_title_box + "<div class='js-module-sidebar-settings-menu-holder'>" + "</div>";
    sidebar_title_box = sidebar_title_box + "<div class='mw_module_settings_sidebar_title'>" + mod_title + "</div>";

    if (mod_title != mod_descr) {
        //  sidebar_title_box = sidebar_title_box + "<div class='mw_module_settings_sidebar_description'>" + mod_descr + "</div>";
    }
    sidebar_title_box = sidebar_title_box + "</div>";
    return sidebar_title_box;
};

mw.live_edit.getModuleIcon = function (module_type) {
    if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].icon) {
        return '<span class="mw_module_settings_sidebar_icon" style="background-image: url(' + mw.live_edit.registry[module_type].icon + ')"></span>';
    }
/*    if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].title) {
        return '<span class="mw-handle-handler-title">'+mw.live_edit.registry[module_type].title+'</span>';
    }*/
    else {
        return '<span class="mdi mdi-drag" style="font-size: 20px;"></span>&nbsp;&nbsp;';
    }
};
mw.live_edit.getModuleTitle = function (module_type) {
    if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].title) {
        return mw.live_edit.registry[module_type].title;
    } else if (mw.live_edit.registry[module_type] && mw.live_edit.registry[module_type].name) {
        return mw.live_edit.registry[module_type].name;
    }
    else {
        return '';
    }
};
mw.live_edit.getModuleDescription = function (module_type) {
    if (mw.live_edit.registry[module_type] && typeof(mw.live_edit.registry[module_type].description) != 'undefined') {
        return mw.live_edit.registry[module_type].description;
    }
    else {
        return '';
    }
};


