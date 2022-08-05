<?php
$rand = crc32(serialize($params));
$menu_name = get_option('menu_name', $params['id']);

if ($menu_name == false and isset($params['menu_name'])) {
    $menu_name = $params['menu_name'];
} elseif ($menu_name == false and isset($params['menu-name'])) {
    $menu_name = $params['menu-name'];
} elseif ($menu_name == false and isset($params['name'])) {
    $menu_name = $params['name'];
}
?>

<script>
    mw.require('link-editor.js');
</script>
<script>

    mw.add_new_page_to_menu = function (id) {
        mw.tools.loading(true);

        if (id === undefined) {
            id = 0;
        }

        MenuTabs.set(3);

        mw.$('#mw_page_create_live_edit').removeAttr('data-content-id');

        mw.$('#mw_page_create_live_edit').attr('from_live_edit', 1);
        mw.$('#mw_page_create_live_edit').attr('content_type', 'page');
        mw.$('#mw_page_create_live_edit').attr('content-id', id);
        mw.$('#mw_page_create_live_edit').attr('quick_edit', 1);
        mw.$('#mw_page_create_live_edit').removeAttr('live_edit');
        mw.$('#mw_page_create_live_edit').attr('add-to-menu', '<?php print $menu_name ?>');

        var v = mw.$('#menu_selector_<?php  print $params['id'] ?>').val();
        if (v) {
            mw.$('#mw_page_create_live_edit').attr('add-to-menu', v);
        }
        mw.load_module('content/edit_page', '#mw_page_create_live_edit', function () {
            $('.fade-window').removeClass('closed').addClass('active').css({
                position: 'static'
            });
            $(".mw-edit-content-item-admin").css({
                boxShadow: 'none'
            })
            mw.tools.loading(false)
        });
    }

    $(mwd).ready(function () {
        MenuTabs = mw.tabs({
            nav: '#menu-tabs a',
            tabs: '.tab'
        });
    });

</script>

<div class="module-live-edit-settings">
    <!--    <a href="javascript:mw.add_new_page_to_menu();" class="mw-ui-btn mw-ui-btn-medium pull-right">--><?php //_e("Create new page"); ?><!--</a>-->

    <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
        <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#list"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Menus'); ?></a>
        <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        <a class="btn btn-outline-secondary justify-content-center d-none" data-bs-toggle="tab" href="#add_new_menu" id="add_new_menu_tab"><i class="mdi mdi-pencil-ruler mr-1"></i> 1</a>
        <a class="btn btn-outline-secondary justify-content-center d-none" data-bs-toggle="tab" href="#add_new_page" id="add_new_content_tab"><i class="mdi mdi-pencil-ruler mr-1"></i> 2</a>
    </nav>

    <div class="tab-content py-3">
        <div class="tab-pane fade show active" id="list">
            <?php include($config['path_to_module'] . 'admin_live_edit_tab1.php'); ?>
        </div>

        <div class="tab-pane fade" id="templates">
            <module type="admin/modules/templates"/>
        </div>

        <div class="tab-pane fade" id="add_new_menu">
            <input name="menu_id" type="hidden" value="0"/>
            <div style="overflow: hidden">
                <input class="mw-ui-field w100" type="text" name="title" placeholder="<?php _e("Menu Name"); ?>"/>
                <button type="button" class="mw-ui-btn pull-right" onclick="mw.menu_save('#add_new_menu')"><?php _e("Add"); ?></button>
            </div>
        </div>

        <div class="tab-pane fade" id="add_new_page">
            <div id="mw_page_create_live_edit"></div>
        </div>
    </div>
</div>
