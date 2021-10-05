<?php
if (is_admin() == false) {
    mw_error('Must be admin');
}

$id = false;
if (isset($params['menu-id'])) {
    $id = intval($params['menu-id']);
}

if (!isset($params['menu-name']) and isset($params['name'])) {
    $params['menu-name'] = $params['name'];
}

$menu_title = false;
if (isset($params['menu-name'])) {
    $menu = get_menus('one=1&limit=1&title=' . $params['menu-name']);
    if (isset($menu['id'])) {
        $id = intval($menu['id']);
    } else {
        $menu = get_menus('one=1&limit=1&id=' . $params['menu-name']);
        if (isset($menu['id'])) {
            $id = intval($menu['id']);
        }
    }
}

if ($id != 0) {
    $menu_title = $menu['title'];
    $menu_params = array();
    $menu_params['menu_id'] = $id;
    $menu_params['link'] = '
    <div id="menu-item-{id}" data-item-id="{id}" class="module_item d-flex justify-content-between align-items-center py-1 px-2 show-on-hover-root">
        <div class="d-inline-flex align-items-center">
            <i class="mdi mdi-cursor-move mdi-20px text-muted mr-2 show-on-hover mw_admin_modules_sortable_handle"></i>
            <span data-item-id="{id}" class="menu_element_link font-weight-bold {active_class}" onclick="mw.menu_admin.set_edit_item({id}, this, ' . $id . ');">{title}</span>
        </div>
        <div class="d-inline-flex align-items-center">
            <span class="btn btn-outline-primary btn-sm" onclick="mw.menu_admin.set_edit_item({id}, this, ' . $id . ');">' . _e('Edit', true) . '</span>
            <span class="btn btn-link px-2" onclick="mw.menu_admin.delete_item({id});"><i class="mdi mdi-trash-can-outline mdi-20px text-danger"></i></span>
        </div>
	</div>';

    $data = menu_tree($menu_params);
}

?>
<?php $rand = uniqid(); ?>

<script>
    currentMenuId = <?php print $id; ?> ||
    0;
</script>

<script type="text/javascript">
    mw.menu_items_sort_<?php print $rand; ?> = function () {
        if (!mw.$("#mw_admin_menu_items_sort_<?php print $rand; ?>").hasClass("ui-sortable")) {

            $("#mw_admin_menu_items_sort_<?php print $rand; ?> ul:first").nestedSortable({
                items: 'li',
                listType: 'ul',
                handle: '.mw_admin_modules_sortable_handle',
                old_handle: '.iMove',
                attribute: 'data-item-id',
                update: function () {
                    var obj = {ids: [], ids_parents: {}};
                    $(this).find('.menu_element').each(function () {

                        var id = this.attributes['data-item-id'].nodeValue;
                        obj.ids.push(id);
                        var $has_p = $(this).parents('.menu_element:first').attr('data-item-id');
                        if ($has_p != undefined) {
                            obj.ids_parents[id] = $has_p;
                        } else {
                            var $has_p1 = $('#ed_menu_holder').find('[name="parent_id"]').first().val();
                            if ($has_p1 != undefined) {
                                obj.ids_parents[id] = $has_p1;
                            }
                        }
                    });

                    $.post("<?php echo route('api.menu.item.reorder'); ?>", obj, function (msg) {
                        if (mw.notification != undefined) {
                            mw.notification.success('<?php _ejs("Menu changes are saved"); ?>');
                        }
                        mw.menu_admin.after_save_item();
                    });
                },
                start: function (a, ui) {
                    $(this).height($(this).outerHeight());
                    $(ui.placeholder).height($(ui.item).outerHeight())
                    $(ui.placeholder).width($(ui.item).outerWidth())
                },
                placeholder: "custom-field-main-table-placeholder"
            });
        }
    };

    $(document).ready(function () {
        mw.menu_items_sort_<?php print $rand; ?>();

        $(".manage-menus .module_item").addClass('menu-item-box bg-grey');
    });
</script>

<hr class="thin"/>

<?php if (isset($data) and $data != false): ?>
    <div class="form-group">
        <label class="control-label"><strong><?php print strtoupper(str_replace('_', ' ', $menu_title)); ?> <?php _e("structure"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Here you can edit your menu links. You can also drag and drop to reorder them."); ?></small>
    </div>

    <div class="menu-module-wrapper">
        <style  scoped="scoped">
            #mw_admin_menu_items_sort_<?php print $rand; ?> > ul {
                height: auto !important;
            }

            #mw_admin_menu_items_sort_<?php print $rand; ?> > ul ul{
                margin-left: 25px;
            }

            #mw_admin_menu_items_sort_<?php print $rand; ?> > ul li {
                list-style: none;
            }
        </style>

        <div class="manage-menus">
            <div class="mw-modules-admin" id="mw_admin_menu_items_sort_<?php print $rand; ?>"> <?php print $data; ?></div>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                $(".add-custom-link-parent-id").val('<?php print $id ?>');
                $("#add-custom-link-parent-id").val('<?php print $id ?>');
                $(".selected-box:gt(0)").remove();
            });
        </script>

        <module id="ed_menu_holder" data-type="menu/edit_item" item-id="0" menu-id="<?php print $id ?>"/>
    </div>
<?php else: ?>
    <div class="icon-title justify-content-center" style="min-height: 250px">
        <i class="mdi mdi-link-variant"></i>
        <h5 style="margin: 0;"><?php _e("This menu is empty, please add items."); ?></h5>
    </div>
<?php endif; ?>

<?php if ($id != 0): ?>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <p class="m-0 font-weight-bold"><?php print strtoupper(str_replace('_', ' ', $menu_title)) . ' '; ?><?php _e("Selected"); ?></p>
        <a href="javascript: mw.menu_delete('<?php print $id; ?>');" class="btn btn-outline-danger btn-sm">Delete&nbsp;<strong><?php print strtoupper(str_replace('_', ' ', $menu_title)); ?></strong></a>
    </div>
<?php endif; ?>
