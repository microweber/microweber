<?php
if(is_admin() == false){
    mw_error('Must be admin');
}
$id = false;
if(isset($params['menu-id'])){
    $id = intval($params['menu-id']);
}
if(!isset($params['menu-name']) and isset($params['name'])){
    $params['menu-name'] = $params['name'];
}

if(isset($params['menu-name'])){
    //$id = trim($params['menu-name']);
    $menu = get_menus('one=1&limit=1&title='.$params['menu-name']);
    if(isset($menu['id'])){
        $id = intval($menu['id']);

    } else {
        $menu = get_menus('one=1&limit=1&id='.$params['menu-name']);
        if(isset($menu['id'])){
            $id = intval($menu['id']);

        }
    }
}

if( $id != 0){
    $menu_params = array();
    $menu_params['menu_id'] =  $id;
    $menu_params['link'] = '<div id="menu-item-{id}" data-item-id="{id}"  class="module_item"><span class="mw-ui-btn mw-ui-btn-small show-on-hover pull-right" onclick="mw.menu_item_delete({id});">'. _e('Delete', true) .'</span><span class="mw-ui-btn mw-ui-btn-small show-on-hover pull-right" onclick="mw.menu_items_set_edit({id}, this);">'. _e('Edit', true) .'</span>
	<span class="mw-icon-drag mw_admin_modules_sortable_handle"></span><span data-item-id="{id}" class="menu_element_link {active_class}" onclick="mw.menu_items_set_edit({id}, this);">{title}</span> </div>';

    $data = menu_tree( $menu_params);
}

?>
<?php  $rand = uniqid(); ?>
<?php if(isset($data) and $data != false): ?>
<script  type="text/javascript">
    mw.require('forms.js', true);
</script>
<script  type="text/javascript">

    mw.require('<?php print $config['url_to_module'] ?>jquery.mjs.nestedSortable.js', true);
</script>
<script  type="text/javascript">

    mw.menu_curenlty_editing_item_id = false;


    mw.menu_save_new_item = function(selector){


        var data = mw.form.serialize(selector)



        $.post( "<?php print api_link('content/menu_item_save'); ?>",data, function( msg ) {
            if(mw.notification != undefined){
                mw.notification.success('<?php _e('Menu changes are saved'); ?>');
            }

            mw.menu_item_after_save();
        });



        /*  	mw.form.post(selector, '<?php print api_link('content/menu_item_save'); ?>', function(){

         if(mw.notification != undefined){
         mw.notification.success('Menu changes are saved');
         }

         mw.menu_item_after_save();
         });*/
    }


    mw.menu_item_after_save = function(){
        mw.reload_module_parent('menu')


        if(mw.menu_curenlty_editing_item_id == false){
            mw.reload_module('menu/edit_items');
        }



        if(mw.menu_curenlty_editing_item_id != false){



            if(mw.$("#edit-menu_item_edit_wrap-"+mw.menu_curenlty_editing_item_id).length>0){
                //	mw.reload_module_parent("#edit-menu_item_edit_wrap-"+mw.menu_curenlty_editing_item_id)

            }
        }

    }
    mw.menu_item_delete = function($item_id){
        mw.tools.confirm(mw.msg.del, function(){
            $.get("<?php print api_link('content/menu_item_delete'); ?>/?id="+$item_id, function(){
                mw.$('#mw_admin_menu_items_sort_<?php print $rand; ?>').find('li[data-item-id="'+$item_id+'"]').fadeOut();
                mw.menu_item_after_save();

            });
        });
    }


    mw.menu_items_set_edit = function($item_id, node){




        if(typeof node === 'object'){
            var li = mw.tools.firstParentWithTag(node, 'li');
            var id = $(li).dataset('item-id');


            var master = mw.tools.firstParentWithClass(node, 'mw-modules-admin');

            mw.$('li .active', master).removeClass('active');

            $(node.parentNode).addClass('active');
            mw.menu_curenlty_editing_item_id = id;
            if(mw.$("#edit-menu_item_edit_wrap-"+id).length>0){
                return false;
            }
            else{

            }
        }

        var the_li = mw.$('#mw_admin_menu_items_sort_<?php print $rand; ?>').find('li[data-item-id="'+$item_id+'"]');
        var edit_wrap = $('#menu_item_edit_wrap-'+$item_id);
        mw.$('.module-menu-edit-item').remove();
        the_li.find('.module_item').eq(0).after('<div id="edit-menu_item_edit_wrap-'+$item_id+'" item-id="'+$item_id+'"></div>');
        $('#edit-menu_item_edit_wrap-'+$item_id).attr('item-id',$item_id);
        $('#edit-menu_item_edit_wrap-'+$item_id).attr('menu-id','<?php print $id?>');
        mw.tools.loading(the_li[0], true);
        mw.load_module('menu/edit_item','#edit-menu_item_edit_wrap-'+$item_id, function(){
            mw.$('#custom_link_inline_controller').show();

            menuSelectorInit("#menu-selector-"+$item_id);
            mwd.querySelector('#custom_link_inline_controller input[type="text"]').focus();
            mw.tools.loading(the_li[0], false);
        });
        $('#ed_menu_holder').hide();
    }





    mw.menu_items_sort_<?php print $rand; ?> = function(){
        if(!mw.$("#mw_admin_menu_items_sort_<?php print $rand; ?>").hasClass("ui-sortable")){
            $("#mw_admin_menu_items_sort_<?php print $rand; ?> ul:first").nestedSortable({
                items: 'li',
                listType: 'ul',
                handle: '.mw_admin_modules_sortable_handle',
                old_handle: '.iMove',
                attribute : 'data-item-id',
                update:function(){
                    var obj = {ids:[], ids_parents:{}}
                    $(this).find('.menu_element').each(function(){
                        var id = this.attributes['data-item-id'].nodeValue;
                        obj.ids.push(id);
                        var $has_p =  $(this).parents('.menu_element:first').attr('data-item-id');
                        if($has_p != undefined){
                            obj.ids_parents[id] = $has_p;
                        }
                        else {
                            var $has_p1 =  $('#ed_menu_holder').find('[name="parent_id"]').first().val();
                            if($has_p1 != undefined){
                                obj.ids_parents[id] =$has_p1;
                            }
                        }
                    });
                    $.post("<?php print api_link('content/menu_items_reorder'); ?>", obj,function(msg){

                        if(mw.notification != undefined){
                            mw.notification.success('Menu changes are saved');
                        }
                        mw.menu_item_after_save();

                    });

                },
                start:function(a,ui){
                    $(this).height($(this).outerHeight());
                    $(ui.placeholder).height($(ui.item).outerHeight())
                    $(ui.placeholder).width($(ui.item).outerWidth())
                },
                placeholder: "custom-field-main-table-placeholder"
            });
        }
    }

    $(document).ready(function(){
        mw.menu_items_sort_<?php print $rand; ?>();


    });
</script>

<div class="menu-module-wrapper">
    <style type="text/css" scoped="scoped">
        #mw_admin_menu_items_sort_<?php print $rand; ?> > ul{
            height: auto !important;
        }

        .manage-menus{
            max-width: 650px;
        }
        .menu_element_link {
            width:80%;
        }

    </style>
    <div class="mw-ui-box mw-ui-box-content manage-menus">
        <div class="mw-modules-admin" id="mw_admin_menu_items_sort_<?php print $rand; ?>"> <?php print $data; ?></div>
    </div>
    <?php else: ?>
        <?php _e("This menu is empty, please add items."); ?>
    <?php endif; ?>
    <script  type="text/javascript">
        $(document).ready(function(){
            $(".add-custom-link-parent-id").val('<?php print $id ?>');
            $("#add-custom-link-parent-id").val('<?php print $id ?>');




        });

    </script>
    <div>
        <module id="ed_menu_holder" data-type="menu/edit_item" item-id="0" menu-id="<?php print $id ?>" />
    </div>
    <?php
    if(isset($params['menu-name'])): ?>
        <?php
        $menu = get_menus('one=1&limit=1&title='.$params['menu-name']);
        if(isset($menu['id'])) :
            ?>
            <a class="mw-ui-btn mw-ui-btn-small mw-ui-btn-important pull-right" href="javascript:mw.menu_delete('<?php print $menu['id']; ?>');">
                <?php _e("Delete"); ?>
                <strong><?php print $menu['title']; ?></strong></a>
        <?php endif ?>
    <?php endif ?>
</div>
