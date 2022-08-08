<?php

use MicroweberPackages\Multilanguage\MultilanguageHelpers;

$rand = uniqid();
if (is_admin() == false) {
    mw_error('Must be admin');
}

$id = false;
if (isset($params['item-id'])) {
    $id = intval($params['item-id']);
}

if ($id == 0) {
    $data = array();
    $data['id'] = $id;
    $data['parent_id'] = 0;
    if (isset($params['parent_id'])) {
        $data['parent_id'] = intval($params['parent_id']);
    } else if (isset($params['menu-id'])) {
        $data['parent_id'] = intval($params['menu-id']);
    }
    if (!isset($params['content_id'])) {
        $data['content_id'] = '';
    } else {
        $data['content_id'] = $params['content_id'];
    }
    if (!isset($params['categories_id'])) {
        $data['categories_id'] = '';
    } else {
        $data['categories_id'] = $params['categories_id'];
    }
    $data['is_active'] = 1;
    $data['position'] = '9999';
    $data['url'] = '';
    $data['title'] = '';
    $data['auto_populate'] = '';
} else {
    $data = mw()->menu_manager->menu_item_get($id);
}
?>

<?php if ($data != false): ?>
<div class="<?php print $config['module_class']; ?> menu_item_edit" id="mw_content/menu_item_save_<?php print $rand ?>">
    <?php if ((!isset($data['title']) or $data['title'] == '') and isset($data["content_id"]) and intval($data["content_id"]) > 0): ?>
        <?php
        $cont = get_content_by_id($data["content_id"]);
        if (isset($cont['title'])) {
            $data['title'] = $cont['title'];
            $item_url = content_link($cont['id']);
        }
        ?>
    <?php else: ?>
        <?php if ((!isset($data['title']) or $data['title'] == '') and isset($data["categories_id"]) and intval($data["categories_id"]) > 0): ?>
            <?php $cont = get_category_by_id($data["categories_id"]);
            if (isset($cont['title'])) {
                $data['title'] = $cont['title'];
                $item_url = category_link($cont['id']);
            }
            ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    if (isset($data['content_id']) and intval($data['content_id']) != 0) {
        $item_url = content_link($data['content_id']);
    }

    if (isset($data['categories_id']) and intval($data['categories_id']) != 0) {
        $item_url = category_link($data['categories_id']);
    }

    if ((isset($item_url) and $item_url != false) and (!isset($data['url']) or trim($data['url']) == '')) {
        $data['url'] = $item_url;
    }
    ?>
    <div id="custom_link_inline_controller" style="display: none;">
        <div id="custom_link_inline_controller_edit_<?php print $data['id'] ?>" class="px-4 py-2 menu-item-box">
            <?php
            if (!isset($data['default_image'])) {
                $data['default_image'] = '';
            }

            if (!isset($data['rollover_image'])) {
                $data['rollover_image'] = '';
            }

            if (!isset($data['size'])) {
                $data['size'] = '';
            }

            if (!isset($data['auto_populate'])) {
                $data['auto_populate'] = '';
            }
            ?>

            <div class="row">
                <div class="col">

                    <div class="form-group">
                        <label class="control-label text-muted"><span class="font-weight-normal"><?php _e("Edit menu item"); ?></span></label>
                        <input type="hidden" name="id" value="<?php print $data['id'] ?>"/>
                        <input type="hidden" id="item_categories_id" value="<?php  print $data['categories_id'] ?>">
                        <input type="hidden" id="item_content_id" value="<?php print $data['content_id'] ?>">

                        <?php



                        $menuModel = \MicroweberPackages\Menu\Menu::where('id', $data['id'])->first();
                        $formBuilder = App::make(\MicroweberPackages\Form\FormElementBuilder::class);

                        echo $formBuilder->text('title')
                            ->setModel($menuModel)
                            ->placeholder($data['title'])
                            ->value($data['title'])
                            ->autofocus(true);
                        ?>
                    </div>

                    <?php if (isset($params['menu-id'])): ?>
                        <input type="hidden" name="parent_id" value="<?php print $params['menu-id'] ?>"/>
                    <?php endif; ?>


                    <?php if (!MultilanguageHelpers::multilanguageIsEnabled()): ?>

                    <div class="form-group change-url-box">
                        <div class="input-group mb-3 append-transparent">

                            <input type="text" class="form-control" id="id-<?php print $data['id'] ?>" placeholder="<?php _e("URL"); ?>" autocomplete="off" name="url" value="<?php print $data['url'] ?>">
                            <div class="input-group-append">
                                <span class="input-group-text" for="id-<?php print $data['id'] ?>" data-for="id-<?php print $data['id'] ?>"><i class="mdi mdi-cog-outline mdi-18px text-muted"></i></span>
                            </div>
                        </div>
                    </div>

                    <?php else: ?>


                        <?php



                        $menuModel = \MicroweberPackages\Menu\Menu::where('id', $data['id'])->first();
                        $formBuilder = App::make(\MicroweberPackages\Form\FormElementBuilder::class);

                        echo $formBuilder->text('url')
                            ->setModel($menuModel)
                            ->placeholder($data['url'])
                            ->value($data['url'])
                            ->autofocus(true);
                        ?>


                    <?php endif; ?>
                </div>
                <div class="col-auto">
                    <label class="control-label d-block">&nbsp;</label>
                    <button type="button" class="btn btn-outline-primary" onclick="mw.$('#menu-selector-<?php print $data['id'] ?>adv').toggle();"><?php _e("Advanced"); ?></button>

                </div>







            </div>



            <?php if ($data['id'] != 0): ?>
                <div>
                    <?php
                    /*  <br>
                      <div  class="mw-ui-field-holder">
                          <label class="mw-ui-label">Auto add to menu <small class="mw-help" data-help="This option will populate the menu automatically with sub-pages and sub-categories"> (?)</small></label>
                          <select name="auto_populate" class="mw-ui-field mw-ui-field-medium">
                              <option  value="" <?php if($data['auto_populate'] == '') : ?>  selected   <?php endif; ?>   >Default</option>
                              <option  value="all" <?php if($data['auto_populate'] == 'all') : ?>  selected   <?php endif; ?> >Add Pages and Categories</option>
                              <option  value="pages" <?php if($data['auto_populate'] == 'pages') : ?>  selected   <?php endif; ?> >Add only Pages</option>
                              <option  value="categories" <?php if($data['auto_populate'] == 'categories') : ?>  selected   <?php endif; ?> >Add only Categories</option>
                          </select>
                      </div>*/
                    ?>

                    <div class="row" id="menu-selector-<?php print $data['id'] ?>adv" style="display: none">
                        <div class="col-sm-6">
                            <?php
                            if (!isset($data['url_target'])) {
                                $data['url_target'] = '';
                            }
                            ?>

                            <div class="form-group">
                                <label class="control-label"><?php _e("Target attribute"); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e("Open the link in New window, Current window, Parent window or Top window"); ?></small>

                                <select class="selectpicker" data-width="100%" data-size="4" name="url_target">
                                    <?php
                                    $attributeValues = explode("|", "|_blank|_self|_parent|_top|framename");
                                    foreach ($attributeValues as $attributeValue):
                                        ?>
                                        <option value="<?php echo $attributeValue; ?>" <?php if ($data['url_target'] == $attributeValue): ?>selected="selected"<?php endif; ?>><?php echo $attributeValue; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label d-block"><?php _e("Select image for menu item"); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e("Attach image for the item"); ?></small>
                                <button class="btn btn-outline-primary btn-sm" onclick="toggle_menu_image_rollover_select<?php print $data['id'] ?>()"><?php _e("Select images"); ?></button>
                            </div>
                        </div>
                    </div>

                    <script>
                        function toggle_menu_image_rollover_select<?php print $data['id'] ?>() {
                            var visible = mw.$('#menu-selector-<?php print $data['id'] ?>b').toggle().is(":visible");
                            if (visible) {
                                mw.reload_module('#menu-selector-image_rollover-load<?php print $data['id'] ?>b')
                            } else {
                                $('#menu-selector-image_rollover-load<?php print $data['id'] ?>b').html('');
                            }
                        }
                    </script>

                    <div id="menu-selector-<?php print $data['id'] ?>b" class="mw-ui mw-ui-category-selector" style="display: none;">
                        <div id="menu-selector-image_rollover-load<?php print $data['id'] ?>b" module="image_rollover" view="admin" menu_rollover="true" size="<?php print $data['size'] ?>" default_image="<?php print $data['default_image'] ?>" rollover_image="<?php print $data['rollover_image'] ?>" for="content"/>
                    </div>
                </div>

                <hr class="thin no-padding">

                <div class="d-flex justify-content-between">
                    <button onclick="cancel_editing_menu(<?php print $data['id'] ?>);" class="btn btn-outline-primary btn-sm"><?php _e("Cancel"); ?></button>
                    <button class="btn btn-success btn-sm" onclick="mw.menu_save_new_item('#custom_link_inline_controller_edit_<?php print $data['id'] ?>');"><?php _e("Save"); ?></button>
                </div>

                <input type="hidden" name="id" value="<?php print $data['id'] ?>"/>
                <input type="hidden" name="content_id" value="<?php print $data['content_id'] ?>"/>
                <input type="hidden" name="categories_id" value="<?php print $data['categories_id'] ?>"/>

                <?php if (isset($params['menu-parent-id'])): ?>
                    <input type="hidden" name="parent_id" value="<?php print $params['menu-parent-id'] ?>"/>
                <?php elseif (isset($data['parent_id']) and $data['parent_id'] != 0): ?>
                    <input type="hidden" name="parent_id" value="<?php print $data['parent_id'] ?>"/>
                <?php elseif (isset($params['parent_id'])): ?>
                    <input type="hidden" name="parent_id" value="<?php print $params['parent_id'] ?>"/>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <?php endif; ?>

    <script>
        cancel_editing_menu = function (id) {
            $("#menu-item-" + id).removeClass('active');
            $("#edit-menu_item_edit_wrap-" + id).remove();
        };

        mw.require('link-editor.js');

        var $data = <?php print json_encode($data); ?>;


        $(document).ready(function () {

            $('.change-url-box .input-group-text, .change-url-box input').on('click', function () {
                var scope = this;

                var conf = {
                    mode: 'dialog',
                    controllers: [

                        { type: 'url', config: {target: false}},
                        { type: 'page', config: {target: false} },
                        { type: 'post', config: {target: false}},
                        { type: 'layout', config: {target: false} }
                    ]
                }



                var contentId = Number(document.querySelector('#item_content_id').value.trim());
                var categoriesId = Number(document.querySelector('#item_categories_id').value.trim());

                if(categoriesId) {
                    conf.selectedIndex = conf.controllers.findIndex(function (itm){ return itm.type === 'page' })
                }
                if(contentId) {
                    conf.selectedIndex = conf.controllers.findIndex(function (itm){ return itm.type === 'page' })
                }



                var linkEditor = new (mw.top().LinkEditor)(conf);

                var root = $(scope).parents(".col");
                linkEditor.setValue({
                    text: root.find('[name="title"]').val(),
                    url: root.find('[name="url"]').val()
                })



                linkEditor.promise().then(function (ldata){

                    if (!ldata) {
                        return
                    }




                    var url = ldata.url,
                        target = ldata.target,
                        name = ldata.text,
                        data = ldata.data;



                    root.find('[name="title"]').val(name);
                    root.find('[name="url"]').val(url);



                    var parent = mw.tools.firstParentWithClass(this, 'mw-ui-gbox');
                    var fields = mw.$('[name="content_id"], [name="categories_id"]', parent).val('0');




                    if (data) {
                        var type = data.subtype || data.type;
                        if (type === 'category') {
                            fields.filter('[name="categories_id"]').val(data.id);
                            fields.filter('[name="content_id"]').val(0);
                        } else if (type) {
                            fields.filter('[name="content_id"]', parent).val(data.id)
                            fields.filter('[name="categories_id"]', parent).val(0)
                        }
                    }
                    if (scope.nodeName === 'INPUT') {
                        scope.value = url;
                        $(scope).trigger('change')
                    } else {
                        if (scope.dataset.for) {
                            var field = $('#' + scope.dataset.for);
                            field.val(url);
                            field.trigger('change')
                        }
                    }
                    //link.dialog.remove();
                });

                //})
                /*$(link.frame).on('load', function () {
                 $('#customweburl_text_field_holder', this.contentWindow.document).hide()
                 })*/
            })
        })
    </script>
