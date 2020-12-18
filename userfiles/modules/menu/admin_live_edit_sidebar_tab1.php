<script type="text/javascript">
    mw.require('tree.js', true);
    mw.require('forms.js', true);
    mw.require('url.js', true);
</script>
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
    }

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
        var data = {}
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



    view_all_subs = function () {
        var master = mwd.querySelector('.mw-modules-admin');
        $(master.querySelectorAll('.menu_nested_controll_arrow')).each(function () {
            $(this).addClass('toggler-active');
            $(this.parentNode.parentNode.querySelector('ul')).addClass('toggle-active').show();
        });

        $(".view_all_subs").addClass('active');
        $(".hide_all_subs").removeClass('active');
    }

    hide_all_subs = function () {
        var master = mwd.querySelector('.mw-modules-admin');
        $(master.querySelectorAll('.menu_nested_controll_arrow')).each(function () {
            $(this).removeClass('toggler-active');
            $(this.parentNode.parentNode.querySelector('ul')).removeClass('toggle-active').hide();
        });
        $(".view_all_subs").removeClass('active');
        $(".hide_all_subs").addClass('active');
    }

    cancel_editing_menu = function (id) {
        $("#menu-item-" + id).removeClass('active');
        $("#edit-menu_item_edit_wrap-" + id).remove();
    };




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

<div class="admin-side-content">
    <?php if (is_array($menus) == true): ?>
        <?php if (is_array($menus)): ?>

            <div class="control-group form-group">
                <label class="mw-ui-label"><?php _e("Select the Menu you want show"); ?></label>

                <select id="menu_selector_<?php print $params['id'] ?>" style="width: 100%;" name="menu_name" class="mw-ui-field mw_option_field" type="radio" onchange="mw.menu_edit_items(this.value, '#items_list_<?php print $rand ?>');"
                        onblur="mw.menu_edit_items(this.value, '#items_list_<?php print$rand ?>');">
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
        <p><?php _e("You have no exising menus. Please create one."); ?></p>
    <?php endif; ?>
</div>
