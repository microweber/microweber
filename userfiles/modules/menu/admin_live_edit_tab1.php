<script type="text/javascript">
    mw.require('tree.js', true);
    mw.require('forms.js', true);
    mw.require('url.js', true);
    mw.require('<?php print $config['url_to_module'] ?>jquery.mjs.nestedSortable.js', true);
</script>

<script>
    $(window).on('load', function () {
        if (window.thismodal) {
            thismodal.width(700);
            thismodal.center(700);
        }
    });

    var addMenuItem = function () {
        var mwt = mw.top()
        var linkEditor = new mwt.LinkEditor({
            mode: 'dialog',
            controllers: [
                { type: 'page', config: {target: false} },
                { type: 'post', config: {target: false}},
                { type: 'url', config: {target: false}},
                { type: 'layout', config: {target: false} }
            ]
        });


        linkEditor.promise().then(function (res){


            var data = {
                title: res.text || res.url.split('/').pop(),
                url: res.url,
                parent_id: currentMenuId
            };

            if (res.data) {
                if (res.data.type === 'page') {
                    data.content_id = res.data.id;
                    data.url = null;
                    data.title = null;

                } else if (res.data.type === 'category') {
                    data.categories_id = res.data.id;
                    data.url = null;
                    data.title = null;
                }
            }

            mw.menu_admin.save_item(data);
        })


    }
</script>

<?php
if (!isset($rand)) {
    $rand = uniqid();
}
?>

<script type="text/javascript">
    mw.menu_add_new = function () {
        var obj = {};
        obj.title = $('#new_menu_name').val();
        $.post("<?php echo route('api.menu.create'); ?>", obj, function (data) {
            window.location.href = window.location.href;
        });
    };

    mw.menu_save = function ($selector) {
        var obj = mw.form.serialize($selector);
        $.post("<?php echo route('api.menu.create'); ?>", obj, function (data) {
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
        if (confirm('<?php _ejs('Are you sure you want to delete this menu?'); ?>') === true) {
            $.post("<?php echo route('api.menu.delete'); ?>", data, function (resp) {
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
                if (item.type === 'page') {
                    data.content_id = item.id;
                }
                if (item.type === 'category') {
                    data.categories_id = item.id;
                }

                data.parent_id = $("#add-custom-link-parent-id").val();
                requestLink();
                $.post("<?php print route('api.menu.item.save'); ?>", data, function (msg) {
                    mw.top().reload_module('menu');
                    mw.reload_module('menu/edit_items');
                });
            })
        });
    });

    if (typeof mw.menu_save_new_item !== 'function') {
        mw.menu_save_new_item = function (selector, no_reload) {
            var _onReady = function () {
                mw.$('#<?php print $params['id'] ?>').removeAttr('new-menu-id');
                if (no_reload === undefined) {
                    mw.reload_module('menu/edit_items');

                 }
                if (self !== parent && typeof parent.mw === 'object') {
                    mw.parent().reload_module('menu');
                }
            }

            mw.form.post(selector, '<?php print route('api.menu.item.save'); ?>', _onReady, undefined, undefined, undefined, function (postData) {
                var data = $.extend({}, postData);
                if (!!data.categories_id && data.categories_id !== '0') {
                    data.url = '';
                }
                if (!!data.content_id && data.content_id !== '0') {
                    data.url = '';
                }
                return data;
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
    currentMenuId = <?php print $menu_id; ?> ||
    0;
</script>
<div id="quick_new_menu_holder">
    <div id="create-menu-holder" style="display: none;">
        <div class="alert alert-success" >
        <div class="form-inline">
            <div class="form-group  mb-3">
                <label class="form-label d-block w-100 mb-1"><?php _e('Create new menu'); ?></label>
               <div class="d-flex align-items-center gap-2">
                   <input name="new_menu_name" class="form-control" id="new_menu_name" placeholder="<?php _e("Menu name"); ?>" type="text" style="margin-right: 12px;"/>
                   <button type="button" class="btn btn-success" onclick="mw.menu_add_new()"><?php _e("Save"); ?></button>
               </div>
            </div>
        </div>
        </div>
    </div>
</div>

<?php if (is_array($menus) == true): ?>
    <?php if (is_array($menus)): ?>

        <div class="form-group mb-3">
            <label class="form-label font-weight-bold mb-2 d-flex align-items-center justify-content-between">
                <?php _e("Select the Menu you want to edit"); ?>
                <button onclick="add_new_menu();" class="btn btn-link mw-admin-action-links text-decoration-none" id="create-menu-btn"><?php _e("Create new menu"); ?></button>
            </label>

            <select id="menu_selector_<?php print $params['id'] ?>" name="menu_name" class="mw_option_field form-select" data-width="100%" data-size="5" onchange="mw.menu_edit_items(this.value, '#items_list_<?php print $rand ?>');" onblur="mw.menu_edit_items(this.value, '#items_list_<?php print $rand ?>');">
                <?php foreach ($menus as $item): ?>
                    <?php
                    if ($active_menu == false) {
                        $active_menu = $item['title'];
                    }
                    ?>
                    <option <?php if ($menu_name == $item['title'] or $menu_data == $item['id']): ?><?php $active_menu = $item['title'] ?>selected<?php endif; ?> value="<?php print $item['title'] ?>"><?php print ucwords(str_replace('_', ' ', $item['title'])) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
<?php else : ?>
    <div class="icon-title justify-content-center">
        <i class="mdi mdi-menu"></i>
        <h5 class="mb-0"><?php _e("You have no exising menus. Please create one."); ?></h5>
        <button   onclick="add_new_menu();" class="btn btn-primary" id="create-menu-btn"><?php _e("Create new menu"); ?></button>
    </div>
<?php endif; ?>
<?php
if (isset($menu_data) and is_array($menu_data) and isset($menu_data['id'])) {
    $menu_data = $menu_data['id'];
}
?>

<div class="d-block">
    <button type="button" class="btn btn-dark mt-2" onclick="addMenuItem()"> <?php _e("Add menu item"); ?></button>
</div>

<div class="<?php print $config['module_class']; ?> menu_items order-has-link">
    <?php if ($active_menu != false): ?>
        <module data-type="menu/edit_items" id="items_list_<?php print $rand ?>" menu-name="<?php print $active_menu ?>" menu-id="<?php print $menu_id ?>"/>
    <?php endif; ?>
</div>

<div id="link-selector-holder" style="display: none"></div>

<script><?php print file_get_contents(__DIR__ . DS . 'menu_admin.js'); ?></script>
