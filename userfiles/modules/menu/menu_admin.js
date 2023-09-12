mw.menu_admin = {};

mw.menu_admin.curenlty_editing_item_id = null;
mw.menu_admin.saving = null;



mw.menu_admin.save_item = function(selectorOrData){
    mw.tools.loading(document.querySelector('#settings-main'), 90);
    if(mw.menu_admin.saving) return;
    mw.menu_admin.saving = true;
    var data;
    if(typeof selectorOrData === 'string' || mw.$(selectorOrData)[0].nodeName) {
        data = mw.form.serialize(selectorOrData);
    } else {
        data = selectorOrData;
    }

    return $.post( mw.settings.api_url + "menu/item/save",data, function( msg ) {
        if(mw.notification != undefined){
            mw.notification.success(mw.msg.settingsSaved);
        }
        mw.tools.loading(document.querySelector('#settings-main'), false);
        mw.menu_admin.after_save_item();
        mw.menu_admin.saving = false;

    });
};

mw.menu_admin.after_save_item = function(){

    $('#layout_link_controller').hide();
    var layoutRadio = $('#layout_link_controller input:checked')[0];
    if (layoutRadio) {
        layoutRadio.checked = false;
        $('#ltext').val('');
    }

    if(mw.menu_admin.curenlty_editing_item_id == false){
        //mw.reload_module('menu/edit_items');
    }
    mw.reload_module('menu/edit_items');

    if(mw.menu_admin.curenlty_editing_item_id != false){
        if(mw.$("#edit-menu_item_edit_wrap-"+mw.menu_admin.curenlty_editing_item_id).length>0){
            //	mw.reload_module_parent("#edit-menu_item_edit_wrap-"+mw.menu_admin.curenlty_editing_item_id)

        }

    }
    mw.top().reload_module_everywhere('menu');
 
};
mw.menu_admin.delete_item = function($item_id){

    mw.tools.confirm(mw.msg.del, function(){
        $.post(mw.settings.api_url +"menu/item/delete/"+$item_id, function(){
            var master = $('#settings-main');

            mw.$(master).find('li[data-item-id="'+$item_id+'"]').fadeOut();
            mw.menu_admin.after_save_item();

        });
    });
};


mw.menu_admin.set_edit_item = function($item_id, node, $id){
    if(typeof node === 'object'){
        var li = mw.tools.firstParentWithTag(node, 'li');
        var id = $(li).dataset('item-id');

        var master = mw.tools.firstParentWithClass(node, 'mw-modules-admin');

        mw.$('li .active', master).removeClass('active');
        mw.$('ul .active', master).removeClass('active');

        $(node.parentNode).addClass('active');
        $(node.parentNode).parent().addClass('opened');
        mw.menu_admin.curenlty_editing_item_id = id;
        if(mw.$("#edit-menu_item_edit_wrap-" + id).length>0){
            return false;
        }
        else{

        }
    }

    var the_li = mw.$(master).find('li[data-item-id="'+$item_id+'"]');
    var edit_wrap = $('#menu_item_edit_wrap-'+$item_id);
    mw.$('.module-menu-edit-item').remove();
    the_li.find('.module_item').eq(0).after('<div id="edit-menu_item_edit_wrap-'+$item_id+'" item-id="'+$item_id+'"></div>');
    $('#edit-menu_item_edit_wrap-'+$item_id).attr('item-id',$item_id);
    $('#edit-menu_item_edit_wrap-'+$item_id).attr('menu-id', $id);
    mw.tools.loading(the_li[0], true);
    mw.load_module('menu/edit_item','#edit-menu_item_edit_wrap-'+$item_id, function(){
        mw.$('#custom_link_inline_controller').show();


        document.querySelector('#custom_link_inline_controller input[type="text"]').focus();
        mw.tools.loading(the_li[0], false);
    });
    $('#ed_menu_holder').hide();
};
