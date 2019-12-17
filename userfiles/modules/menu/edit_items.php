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
$menu_title = false;
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

    $menu_title = $menu['title'];

    $menu_params = array();
    $menu_params['menu_id'] =  $id;
    $menu_params['link'] = '
    <div id="menu-item-{id}" data-item-id="{id}"  class="module_item">
    <span class="mw-ui-btn mw-ui-link mw-ui-btn-rounded mw-ui-btn-small show-on-hover pull-right" onclick="mw.menu_admin.delete_item({id});"><i class="mw-icon-app-trash"></i></span>
    <span 
        class="mw-ui-btn mw-ui-link mw-ui-btn-rounded mw-ui-btn-small show-on-hover pull-right" 
        onclick="mw.menu_admin.set_edit_item({id}, this, '.$id.');">'. _e('Edit', true) .'</span>
	<span class="mw-icon-drag mw_admin_modules_sortable_handle"></span>
	<span data-item-id="{id}" class="menu_element_link {active_class}" onclick="mw.menu_admin.set_edit_item({id}, this, '.$id.');">{title}</span> 
	</div>';

    $data = menu_tree( $menu_params);
}

?>
<?php  $rand = uniqid(); ?>







<script  type="text/javascript">


    mw.menu_items_sort_<?php print $rand; ?> = function(){
        if(!mw.$("#mw_admin_menu_items_sort_<?php print $rand; ?>").hasClass("ui-sortable")){
            $("#mw_admin_menu_items_sort_<?php print $rand; ?> ul:first").nestedSortable({
                items: 'li',
                listType: 'ul',
                handle: '.mw_admin_modules_sortable_handle',
                old_handle: '.iMove',
                attribute : 'data-item-id',
                update:function(){
                    var obj = {ids:[], ids_parents:{}};
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
                            mw.notification.success('<?php _ejs("Menu changes are saved"); ?>');
                        }
                        mw.menu_admin.after_save_item();

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
    };

    $(document).ready(function(){
        mw.menu_items_sort_<?php print $rand; ?>();
    });
</script>





<?php if(isset($data) and $data != false): ?>


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
            $(".selected-box:gt(0)").remove();
        });
    </script>
    <div>
        <module id="ed_menu_holder" data-type="menu/edit_item" item-id="0" menu-id="<?php print $id ?>" />
    </div>
</div>
<?php if( $id != 0){ ?>
    <div class="mw-ui-box selected-box  ">
        <div class="mw-ui-box-content">
            <?php _e("You have selected"); ?>
            <em><?php print strtoupper(str_replace('_', ' ', $menu_title)); ?></em>
            <a href="javascript: mw.menu_delete('<?php print $id; ?>');" class="pull-right tip" data-tip="Delete">
                <i class="mw-icon-app-trash-outline"></i>
            </a>
        </div>
    </div>
<?php } ?>
