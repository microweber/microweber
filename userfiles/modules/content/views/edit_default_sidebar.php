<?php if (isset($data['url']) and $data['id'] > 0): ?>
    <script>
        $(document).ready(function () {
            $('.go-live-edit-href-set').attr('href', '<?php print content_link($data['id']); ?>');
        });
    </script>
<?php endif; ?>

<div class="col-md-4 manage-content-sidebar">
    <div class="card style-1 mb-3">
        <div class="card-body pt-3 pb-0">
            <div class="row">
                <div class="col-12">
                    <strong>Visibility</strong>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-12">
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" checked="">
                            <label class="custom-control-label" for="customRadio1">Visible</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                            <label class="custom-control-label" for="customRadio2">Hidden</label>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <a href="#" class="btn btn-link px-0">Set a specific publish date</a>
                </div>
            </div>
        </div>
    </div>


    <div class="card style-1 mb-3 categories">
        <div class="card-body pt-3">
            <div class="row">
                <?php if ($data['content_type'] == 'page') : ?>
                    <div class="col-12">
                        <strong><?php _e("Select parent page"); ?></strong>

                        <div class="quick-parent-selector">
                            <module type="content/views/selector" no-parent-title="<?php _e('No parent page'); ?>" field-name="parent_id_selector" change-field="parent" selected-id="<?php print $data['parent']; ?>" remove_ids="<?php print $data['id']; ?>" recommended-id="<?php print $recommended_parent; ?>"/>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-12">
                        <strong><?php _e('Categories'); ?></strong>
                        <a href="<?php echo admin_url(); ?>view:content/action:categories" class="btn btn-link float-right py-1 px-0">Manage</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <?php if ($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
                        <script>
                            $(document).ready(function () {
                                $('#mw-post-added-<?php print $rand; ?>').on('mousedown touchstart', function (e) {
                                    if (e.target.nodeName === 'DIV') {
                                        setTimeout(function () {
                                            $('.mw-ui-invisible-field', e.target).focus()
                                        }, 78)
                                    }
                                });

                                var all = [{type: 'page', id: <?php print $data['parent']; ?>}];
                                var cats = [<?php print $categories_active_ids; ?>];

                                $.each(cats, function () {
                                    all.push({
                                        type: 'category',
                                        id: this
                                    })
                                });

                                if (typeof(mw.adminPagesTree) != 'undefined') {
                                    mw.adminPagesTree.select(all);
                                }
                            });
                        </script>

                        <div class="mw-tag-selector mt-3" id="mw-post-added-<?php print $rand; ?>">
                            <div class="post-category-tags"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="thin no-padding"/>

            <?php if ($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
                <div class="row mb-3">
                    <div class="col-12">
                        <small class="text-muted">Want to add the product in more categories?</small>
                        <br/>
                        <button type="button" class="btn btn-outline-primary btn-sm text-dark my-3" data-toggle="collapse" data-target="#show-categories-tree">Add to category</button>
                        <br/>

                        <div id="show-categories-tree" class="collapse">
                            <div class="mw-admin-edit-page-primary-settings content-category-selector">
                                <div class="mw-ui-field-holder">


                                    <div class="mw-ui-category-selector mw-ui-category-selector-abs mw-tree mw-tree-selector" id="mw-category-selector-<?php print $rand; ?>">
                                        <?php if ($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
                                            <script>
                                                $(document).ready(function () {
                                                    $.get("<?php print api_url('content/get_admin_js_tree_json'); ?>", function (tdata) {

                                                        var selectedPages = [ <?php print $data['parent']; ?>];
                                                        var selectedCategories = [ <?php print $categories_active_ids; ?>];

                                                        window.categorySelector = new mw.treeTags({
                                                            data: tdata,
                                                            selectable: true,
                                                            multiPageSelect: false,
                                                            tagsHolder: '.post-category-tags',
                                                            treeHolder: '#quick-parent-selector-tree',
                                                            color: 'primary',
                                                            size: 'sm',
                                                            outline: true,
                                                            saveState: false
                                                        });

                                                        $(categorySelector.tree).on('ready', function () {
                                                            if (window.pagesTree && pagesTree.selectedData.length) {
                                                                $.each(pagesTree.selectedData, function () {
                                                                    categorySelector.tree.select(this)
                                                                })
                                                            } else {
                                                                $.each(selectedPages, function () {
                                                                    categorySelector.tree.select(this, 'page')
                                                                });
                                                                $.each(selectedCategories, function () {
                                                                    categorySelector.tree.select(this, 'category')
                                                                });
                                                            }

                                                            var atcmplt = $('<div class="input-group mb-0 prepend-transparent"> <div class="input-group-prepend"> <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span> </div> <input type="text" class="form-control form-control-sm" placeholder="Search"> </div>');

                                                            $("#quick-parent-selector-tree").before(atcmplt);

                                                            atcmplt.find('input').on('input', function () {
                                                                var val = this.value.toLowerCase().trim();
                                                                if (!val) {
                                                                    categorySelector.tree.showAll();
                                                                }
                                                                else {
                                                                    categorySelector.tree.options.data.forEach(function (item) {

                                                                        if (item.title.toLowerCase().indexOf(val) === -1) {
                                                                            categorySelector.tree.hide(item);
                                                                        }
                                                                        else {
                                                                            categorySelector.tree.show(item);
                                                                        }
                                                                    });
                                                                }
                                                            })
                                                        });

                                                        $(categorySelector.tags).on("tagClick", function (e, data) {
                                                            $(".mw-tree-selector").show();
                                                            mw.tools.highlight(categorySelector.tree.get(data))
                                                        });
                                                    });
                                                });
                                            </script>

                                            <div id="quick-parent-selector-tree"></div>

                                        <?php include(__DIR__ . '/edit_default_scripts_two.php'); ?>


                                            <div id="parent-category-selector-block">
                                                <h3><?php _e("Select parent"); ?></h3>

                                                <div id="parent-category-selector-holder"></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card style-1 mb-3">
        <div class="card-body pt-3">
            <div class="row mb-3">
                <div class="col-12">
                    <strong>Tags</strong>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <input class="form-control form-control-sm" type="text" placeholder="healthy, beauty, travel"/>
                    </div>

                    <div class="btn-group tag tag-xs mb-2 mr-1">
                        <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> car</span>
                        <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                    </div>

                    <div class="btn-group tag tag-xs mb-2 mr-1">
                        <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> someother</span>
                        <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                    </div>

                    <div class="btn-group tag tag-xs mb-2 mr-1">
                        <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> topsellproduct</span>
                        <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card style-1 mb-3">
        <div class="right-side">
            <div id="content-title-field-buttons">
                <ul>
                    <?php if ($data['is_active'] == 0) { ?>
                        <li>
                            <button
                                    onclick="mw.admin.postStates.toggle()"
                                    data-val="0"
                                    class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-warn mw-ui-btn-outline btn-posts-state tip"
                                    data-tip="<?php _e("Unpublished"); ?>"
                                    data-tipposition="left-center">
                                <i class="mw-icon-unpublish"></i>&nbsp; <?php _e("Unpublish"); ?></button>
                        </li>
                    <?php } else { ?>
                        <li>
                            <button
                                    onclick="mw.admin.postStates.toggle()"
                                    data-val="1"
                                    class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline btn-posts-state tip"
                                    data-tip="<?php _e("Published"); ?>"
                                    data-tipposition="left-center"><i class="mw-icon-check"></i>&nbsp; <?php _e("Published"); ?></button>
                        </li>
                    <?php } ?>
                    <?php if ($is_live_edit == false) : ?>
                        <li>
                            <button type="submit" class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-live-edit-top-bar-button" onclick="mw.edit_content.handle_form_submit(true);" form="quickform-edit-content">
                                <i class="mai-eye2"></i> <span><?php _e("Live Edit"); ?></span></button>
                        </li>
                        <li>
                            <button type="submit" class="mw-ui-btn mw-ui-btn-notification btn-save js-bottom-save" form="quickform-edit-content"><i class="fa fa-save"></i> <span>&nbsp; <?php print _e('Save'); ?></span></button>
                        </li>
                    <?php else: ?>
                        <?php if ($data['id'] == 0): ?>
                            <li>
                                <button type="submit" class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline mw-live-edit-top-bar-button tip" data-tip="<?php _e("Live Edit"); ?>" data-tipposition="bottom-center" onclick="mw.edit_content.handle_form_submit(true);"
                                        data-text="<?php _e("Live Edit"); ?>" form="quickform-edit-content">
                                    <i class="mai-eye2"></i> <span><?php _e("Live Edit"); ?></span></button>
                            </li>
                        <?php else: ?>
                            <li>
                                <button type="button" class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline mw-live-edit-top-bar-button tip" data-tip="<?php _e("Live Edit"); ?>" data-tipposition="bottom-center" onclick="mw.edit_content.handle_form_submit(true);"
                                        data-text="<?php _e("Live Edit"); ?>"><i class="mai-eye2"></i> <span><?php _e("Live Edit"); ?></span></button>
                            </li>
                        <?php endif; ?>
                        <li>
                            <button type="submit" class="mw-ui-btn mw-ui-btn-notification btn-save js-bottom-save tip" data-tip="<?php _e("Save"); ?>" data-tipposition="bottom-center" form="quickform-edit-content"><i class="fa fa-save"></i> <span>&nbsp; <?php print _e('Save'); ?></span>
                            </button>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
