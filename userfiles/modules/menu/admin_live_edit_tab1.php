<script type="text/javascript">
    mw.require('tree.js', true);
    mw.require('forms.js', true);
    mw.require('url.js', true);
</script>


<script  type="text/javascript">
    mw.require('forms.js', true);
</script>
<script  type="text/javascript">
    mw.require('<?php print $config['url_to_module'] ?>jquery.mjs.nestedSortable.js', true);

</script>

<script>

    $(window).on('load', function(){
        if(window.thismodal) {
            thismodal.width(700);
            thismodal.center(700);
        }
    });
    var addMenuItem = function() {
        var picker = mw.component({
            url: 'link_editor_v2',
            options: {
                target: false,
                text: true,
                controllers: 'page, custom, content, section, layout'
            }
        });
        $(picker).on('Result', function(e, res){
            var data = {
                title: res.text || res.url.split('/').pop(),
                url: res.url,
                parent_id: currentMenuId
            };

            if(res.object){
                if(res.object.type === 'page'){
                    data.content_id = res.object.id;
                } else if(res.object.type === 'category') {
                    data.categories_id = res.object.id;
                }
            }

            mw.menu_admin.save_item(data);
        })
    }

</script>



<style>
    #layout_link_controller{
        padding: 20px 0;
    }
    #layouts-selector{
        padding: 20px;
        margin-top: 15px;
    }
    .page-layout-tab .mw-field{
        width: 100%;
    }
    .mw-modules-admin .change-url-box {
        width: 80%;

    }
    .admin-side-content .mw-ui-btn + .mw-ui-btn{
        margin-left: 10px;
    }
    #link-selector-holder{
        padding-top: 16px;
        clear: both;
    }
</style>
<?php if (!isset($rand)) {
    $rand = uniqid();
} ?>
<script type="text/javascript">


    mw.menu_add_new = function () {
        var obj = {};
        obj.title = $('#new_menu_name').val();
        $.post("<?php print api_link('content/menu_create') ?>", obj, function (data) {
            window.location.href = window.location.href;
        });
    };

    mw.menu_save = function ($selector) {
        var obj = mw.form.serialize($selector);
        $.post("<?php print api_link('content/menu_create') ?>", obj, function (data) {
            window.location.href = window.location.href;
        });
    };


    add_new_menu = function () {
        mw.$("#create-menu-holder").toggle();
        var btn = mw.$('#create-menu-btn');
        btn.toggleClass('active');
        if (btn.hasClass('active')) {
            mw.$('#new_menu_name').focus()
        }

    }

    mw.menu_delete = function ($id) {
        var data = {};
        data.id = $id
        if (confirm('<?php _e('Are you sure you want to delete this menu?'); ?>') === true) {
            $.post("<?php print api_link('menu_delete') ?>", data, function (resp) {
                window.location.href = window.location.href;
            });
        }
    }

    mw.menu_edit_items = function ($menu_name, $selector) {

        mw.$($selector).attr('menu-name', $menu_name);
        mw.load_module('menu/edit_items', $selector);

    };


    $(document).ready(function () {

        $.get("<?php print api_url('content/get_admin_js_tree_json'); ?>", function (tdata) {
            pagesMenuTreeSelector = new mw.tree({
                element: '#tree-selector',
                data: tdata,
                selectable: true,
                singleSelect: true
                //filter:{type:'page'}
            });
            $(pagesMenuTreeSelector).on('selectionChange', function (e, selectedData) {
                var item = selectedData[0];
                var data = {};
                if (item.type == 'page') {
                    data.content_id = item.id;
                }
                if (item.type == 'category') {
                    data.categories_id = item.id;
                }

                data.parent_id = $("#add-custom-link-parent-id").val();
                requestLink()

                $.post("<?php print api_link('content/menu_item_save'); ?>", data, function (msg) {
                    mw.top().reload_module('menu');
                    mw.reload_module('menu/edit_items');
                });
            })
        });

    });

    if (typeof mw.menu_save_new_item !== 'function') {
       mw.menu_save_new_item = function (selector, no_reload) {
        mw.form.post(selector, '<?php print api_link('content/menu_item_save'); ?>', function () {
            mw.$('#<?php print $params['id'] ?>').removeAttr('new-menu-id');
            if (no_reload === undefined) {
                mw.reload_module('menu/edit_items');
            }
            if (self !== parent && typeof parent.mw === 'object') {
                parent.mw.reload_module('menu');
            }

        });
        }
    }
</script>




<?php $menus = get_menus(); ?>
<?php
if (!isset($menu_name)) {
    $menu_name = get_option('menu_name', $params['id']);

    if ($menu_name == false and isset($params['menu_name'])) {
        $menu_name = $params['menu_name'];
    } elseif ($menu_name == false and isset($params['name'])) {

        $menu_name = $params['name'];
    } else {


    }
}

$active_menu = $menu_name;
$menu_id = false;

if ($menu_id == false and $menu_name != false) {
    $menu_id = get_menus('one=1&title=' . $menu_name);
    if ($menu_id == false and isset($params['title'])) {
        mw()->menu_manager->menu_create('id=0&title=' . $params['title']);
        $menu_id = get_menus('one=1&title=' . $menu_name);
    }
}

if (isset($menu_id['title'])) {
    $active_menu = $menu_id['title'];
}
$menu_id = get_menus('one=1&title=' . $menu_name);
if ($menu_id == false) {
    $active_menu = $menu_name = 'header_menu';
    $menu_id = get_menus('one=1&title=' . $menu_name);
}


$menu_data = $menu_id;

$menu_id = 0;


if ($menu_data) {
    $menu_id = $menu_data['id'];
}


?>

<script>
    currentMenuId = <?php print $menu_id; ?> || 0;


</script>

<div class="admin-side-content">


    <?php if (is_array($menus) == true): ?>
        <?php if (is_array($menus)): ?>

            <div class="control-group form-group">
                <label class="mw-ui-label">
                    <?php _e("Select the Menu you want to edit or"); ?> <a href="javascript:add_new_menu();"
                                                                           class="mw-ui-link mw-blue"
                                                                           id="create-menu-btn"><?php _e("Create new menu"); ?></a>
                </label>

                <div id="quick_new_menu_holder">
                    <div class="mw-ui-box mw-ui-box-content pull-right" id="create-menu-holder"
                         style="display: none;margin: 5px 0 12px;">
                        <input name="new_menu_name" class="mw-ui-field" id="new_menu_name"
                               placeholder="<?php _e("Menu name"); ?>" type="text" style="margin-right: 12px;"/>
                        <button type="button" class="mw-ui-btn mw-ui-btn-invert"
                                onclick="mw.menu_add_new()"><?php _e("Save"); ?></button>
                    </div>
                </div>

                <select id="menu_selector_<?php print $params['id'] ?>" style="width: 100%;" name="menu_name"
                        class="mw-ui-field mw_option_field" type="radio"
                        onchange="mw.menu_edit_items(this.value, '#items_list_<?php print $rand ?>');"
                        onblur="mw.menu_edit_items(this.value, '#items_list_<?php
                        print
                            $rand ?>');">

                    <?php foreach ($menus as $item): ?>
                        <?php if ($active_menu == false) {
                            $active_menu = $item['title'];
                        } ?>
                        <option <?php if ($menu_name == $item['title'] or $menu_data == $item['id']): ?><?php $active_menu = $item['title'] ?> selected="selected" <?php endif; ?>
                                value="<?php print $item['title'] ?>"><?php print ucwords(str_replace('_', ' ', $item['title'])) ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
        <?php endif; ?>
    <?php else : ?>
        <?php _e("You have no exising menus. Please create one."); ?>
    <?php endif; ?>
    <?php
    if (isset($menu_data) and is_array($menu_data) and isset($menu_data['id'])) {
        $menu_data = $menu_data['id'];
    }
    ?>
    <div>
        <br>

        <span class="mw-ui-btn mw-ui-btn-info pull-right" onclick="addMenuItem()"><span class="mw-icon-plus"></span> <?php _e("Add menu item"); ?></span>
    </div>

    <div class="<?php print $config['module_class']; ?> menu_items order-has-link" id="items_list_<?php print $rand ?>">
        <?php if ($active_menu != false): ?>
            <h4><?php _e("Menu structure"); ?></h4>
            <label class="mw-ui-label">
                <small>
                    <?php _e("Here you can edit your menu links. You can also drag and drop to reorder them."); ?>
                </small>
            </label>
            <module data-type="menu/edit_items" id="items_list_<?php print $rand ?>"  menu-name="<?php print $active_menu ?>" menu-id="<?php print $menu_id ?>"/>
        <?php endif; ?>
    </div>
    <br>
    <div id="link-selector-holder" style="display: none"></div>
</div>
<script><?php include('menu_admin.js'); ?></script>
