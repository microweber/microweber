<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('List of Pages'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <?php $pages = get_content('content_type=page&limit=1000'); ?>
                <?php $posts_parent_page = get_option('data-parent', $params['id']); ?>
                <?php $posts_maxdepth = get_option('maxdepth', $params['id']); ?>
                <?php $include_categories = get_option('include_categories', $params['id']); ?>

                <script type="text/javascript">
                    mw.add_new_page = function (id) {
                        if (id == undefined) {
                            var id = 0;
                        }

                        var par_page = $("#mw_change_pages_parent_root").val();

                        pTabs.set(3);
                        mw.$('#mw_page_create_live_edit').removeAttr('data-content-id');
                        mw.$('#mw_page_create_live_edit').attr('from_live_edit', 1);
                        mw.$('#mw_page_create_live_edit').attr('content_type', 'page');

                        mw.$('#mw_page_create_live_edit').attr('parent', par_page);

                        mw.$('#mw_page_create_live_edit').attr('content-id', id);
                        mw.$('#mw_page_create_live_edit').attr('quick_edit', 1);
                        mw.$('#mw_page_create_live_edit').removeAttr('live_edit');
                        mw.load_module('content/edit_page', '#mw_page_create_live_edit', function () {

                            mw.$(".preview_frame_wrapper .mw-overlay").removeAttr("onclick");
                            mw.$("#mw_pages_list_tree_live_edit_holder").show().visibilityDefault()

                        })
                    }

                    $(document).ready(function () {
                        mw.$("#mw_change_pages_parent_root").change(function () {
                            var val = this.value;
                            mw.$('#mw_pages_list_tree_live_edit').attr('parent', val);
                            mw.reload_module('#mw_pages_list_tree_live_edit')
                        });

                    });
                </script>
                <script type="text/javascript">mw.require('forms.js', true);</script>
                <script type="text/javascript">
                    mw.on.moduleReload("mw_pages_list_tree_live_edit", function () {
                        mw.manage_pages_sort();

                    });

                    mw.manage_pages_sort = function () {
                        if (!mw.$("#mw_pages_list_tree_live_edit").hasClass("ui-sortable")) {
                            mw.$("#mw_pages_list_tree_live_edit ul").sortable({
                                items: 'li',
                                handle: '.pages_tree_link',
                                update: function () {
                                    var obj = {ids: []}
                                    $(this).find('.pages_tree_link').each(function () {
                                        var id = this.attributes['data-page-id'].nodeValue;
                                        obj.ids.push(id);
                                    });

                                    if (mw.notification != undefined) {
                                        mw.notification.success('Saving...');
                                    }

                                    $.post("<?php print api_link('content/reorder'); ?>", obj, function () {

                                        if (mw.notification != undefined) {
                                            mw.notification.success('Reloading module...');
                                        }
                                        mw.reload_module_parent('pages');
                                    });
                                },
                                start: function (a, ui) {
                                    $(this).height($(this).outerHeight());
                                    $(ui.placeholder).height($(ui.item).outerHeight())
                                    $(ui.placeholder).width($(ui.item).outerWidth())
                                },

                                //placeholder: "custom-field-main-table-placeholder",
                                scroll: false
                            });
                        }
                    }

                    $(document).ready(function () {
                        mw.manage_pages_sort();
                    });
                </script>

                <div class="text-end text-right">
                    <a href="javascript:;" class="btn btn-success btn-sm" onclick="mw.add_new_page();"><i class="mw-icon-page"></i> <?php _e("Add new page"); ?></a>
                </div>

                <div id="mw_page_create_live_edit"></div>
                <div class="clearfix"/>

                <div id="mw_pages_list_tree_live_edit_holder" style="max-width: 300px;">
                    <?php
                    $pt_opts = array();
                    $pt_opts['link'] = '<a data-page-id="{id}" class="pages_tree_link {nest_level}"  data-type="{content_type}"   data-shop="{is_shop}"  subtype="{subtype}"   href="javascript:mw.add_new_page({id})">{title}</a>';
                    $pt_opts['ul_class'] = 'pages_tree cat_tree_live_edit nav flex-column';
                    $pt_opts['ul_class_deep'] = 'dropdown-menu';
                    $pt_opts['li_class'] = 'nav-item';
                    $pt_opts['a_class'] = '';
                    $pt_opts['li_submenu_class'] = 'dropdown';
                    $pt_opts['li_submenu_a_class'] = 'dropdown-toggle';

                    //  pages_tree($pt_opts);
                    ?>

                    <module type="pages" link="javascript:mw.add_new_page({id})" ul-class="pages_tree cat_tree_live_edit" li-class="sub-nav" id="mw_pages_list_tree_live_edit" parent="<?php print $posts_parent_page ?>"/>
                </div>
            </div>

            <div class="tab-pane fade" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-pages-settings">

                    <div class="form-group">
                        <label class="control-label"><?php _e("Pages & Sub-Pages From"); ?></label>
                        <select name="data-parent" id="mw_change_pages_parent_root" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option valie="0" <?php if ((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>><?php _e("None"); ?></option>
                            <?php
                            $pt_opts = array();
                            $pt_opts['link'] = "{empty}{title}";
                            $pt_opts['list_tag'] = " ";
                            $pt_opts['list_item_tag'] = "option";
                            $pt_opts['active_ids'] = $posts_parent_page;
                            $pt_opts['active_code_tag'] = '   selected="selected"  ';

                            pages_tree($pt_opts);
                            ?>
                            <?php if (defined('PAGE_ID')): ?>
                                <option value="<?php print PAGE_ID; ?>">[use current page]</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e("Show Categories from page"); ?></label>

                        <select name="include_categories" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option value="y" <?php if ('y' == $include_categories): ?>   selected="selected"  <?php endif; ?> ><?php _e("Yes"); ?></option>
                            <option value="n" <?php if ('y' != $include_categories): ?>   selected="selected"  <?php endif; ?> ><?php _e("No"); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e("Max depth"); ?></label>

                        <select name="maxdepth" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option value="none" selected>
                                <?php _e("Default"); ?>
                            </option>
                            <?php for ($i = 1; $i < 10; $i++): ?>
                                <option value="<?php print $i ?>" <?php if (($i == $posts_maxdepth)): ?>   selected="selected"  <?php endif; ?>> <?php print $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>


    </div>
</div>
