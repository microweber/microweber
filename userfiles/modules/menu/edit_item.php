<?php
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
//	$data['categories_id'] = '';
} else {

    $data = mw()->menu_manager->menu_item_get($id);
}
if ($id != 0) {
//$data = menu_tree( $id);
}



?>
<?php if ($data != false): ?>
    <?php //$rand = uniqid(); ?>


    <div class="<?php print $config['module_class']; ?> menu_item_edit" id="mw_content/menu_item_save_<?php print $rand ?>">
        <?php if ((!isset($data['title']) or $data['title'] == '') and isset($data["content_id"]) and intval($data["content_id"]) > 0): ?>
            <?php $cont = get_content_by_id($data["content_id"]);
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
        <div id="custom_link_inline_controller" class="mw-ui-gbox" style="display: none;">
            <div id="custom_link_inline_controller_edit_<?php print $data['id'] ?>">

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


                <h4><?php _e("Edit menu item"); ?></h4>

                <input type="hidden" name="id" value="<?php print $data['id'] ?>"/>
                <div class="mw-ui-field-holder">
                    <input type="text" placeholder="<?php _e("Title"); ?>" class="mw-ui-field w100" name="title" value="<?php print $data['title'] ?>"/>
                </div>

                <?php if (isset($params['menu-id'])): ?>
                    <input type="hidden" name="parent_id" value="<?php print $params['menu-id'] ?>"/>
                <?php else: ?>

                <?php endif; ?>

                <div class="mw-field change-url-box">
                    <input type="text" id="id-<?php print $data['id'] ?>" placeholder="<?php _e("URL"); ?>" readonly class="mw-ui-field w100" autocomplete="off" name="url" value="<?php print $data['url'] ?>"/>
                    <span class="mw-ui-btn mw-field-append" data-for="id-<?php print $data['id'] ?>"><span class="mw-icon-gear"></span></span>
                </div>

                <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline" onclick="mw.$('#menu-selector-<?php print $data['id'] ?>adv').toggle();">
                    <?php _e("Advanced"); ?>
                </button>


                <?php if ($data['id'] != 0): ?>

<div id="menu-selector-<?php print $data['id'] ?>adv" style="display: none">



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


    <?php
    if(!isset($data['url_target'])){
        $data['url_target'] = '';
    }
    ?>

    <br>

    <div  class="mw-ui-field-holder">
        <label class="mw-ui-label">Target attribute <small class="mw-help" data-help="Target "> (?)</small></label>

        <select class="mw-ui-field" name="url_target">
        <?php
        $attributeValues = explode("|", "|_blank|_self|_parent|_top|framename");
        foreach ($attributeValues as $attributeValue):
        ?>
       	<option value="<?php echo $attributeValue; ?>" <?php if($data['url_target'] == $attributeValue):?>selected="selected"<?php endif; ?>><?php echo $attributeValue; ?></option>
        <?php endforeach; ?>
		</select>

    </div>



    <br>





    <label class="mw-ui-label">Select image for menu item</label>
                    <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded" onclick="mw.$('#menu-selector-<?php print $data['id'] ?>b').toggle();">
                        <?php _e("Select images"); ?>
                    </button>

                    <div id="menu-selector-<?php print $data['id'] ?>b" class="mw-ui mw-ui-category-selector" style="top: 3px;height:420px">
                        <microweber module="image_rollover" view="admin" menu_rollover="true" size="<?php print $data['size'] ?>" default_image="<?php print $data['default_image'] ?>" rollover_image="<?php print $data['rollover_image'] ?>" for="content"/>
                    </div>
</div>
                <?php endif; ?>

                <hr>

                <div class="mw-clear pull-right">
                    <button onclick="cancel_editing_menu(<?php print $data['id'] ?>);" class="mw-ui-btn mw-ui-btn-medium mw-ui-link"><?php _e("CANCEL"); ?></button>
                    <button class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-rounded" onclick="mw.menu_save_new_item('#custom_link_inline_controller_edit_<?php print $data['id'] ?>');"><?php _e("CONFIRM"); ?></button>
                </div>
                <input type="hidden" name="id" value="<?php print $data['id'] ?>"/>
                <input type="hidden" name="content_id" value="<?php print $data['content_id'] ?>"/>
                <input type="hidden" name="categories_id" value="<?php print $data['categories_id'] ?>"/>

                <?php
                /*  <ul class="mw-ui-inline-list">
                <li><span>Auto populate</span></li>
                          <li>
                            <label class="mw-ui-check">
                                <input type="checkbox" <?php  if(isset($data['auto_populate']) and $data['auto_populate'] != false): ?> checked="" <?php endif; ?> name="auto_populate" value="1"><span></span><span>Yes</span>
                            </label>
                          </li>
                         </ul>*/


                ?>




                <?php if (isset($params['menu-parent-id'])): ?>
                    <input type="hidden" name="parent_id" value="<?php print $params['menu-parent-id'] ?>"/>
                <?php elseif (isset($data['parent_id']) and $data['parent_id'] != 0): ?>
                    <input type="hidden" name="parent_id" value="<?php print $data['parent_id'] ?>"/>
                <?php elseif (isset($params['parent_id'])): ?>
                    <input type="hidden" name="parent_id" value="<?php print $params['parent_id'] ?>"/>
                <?php endif; ?>

            </div>

        </div>
    </div>
<?php else: ?>
<?php endif; ?>

<script>
    cancel_editing_menu = function (id) {
        $("#menu-item-" + id).removeClass('active');
        $("#edit-menu_item_edit_wrap-" + id).remove();
    };
    $(document).ready(function () {

//         $('.change-url-box input').on('change', function(){
// alert(3333)
//         })
        $('.change-url-box .mw-ui-btn, .change-url-box input').on('click', function(){
            var scope = this;
            /*var link = mw.top().instruments.link({
                mode: 'dialog'
            });*/

            //link.handler.on('change', function(e, url, target, name, data){
            var picker = mw.component({
                url: 'link_editor_v2',
                options: {
                    target: false,
                    text: false,
                    controllers: 'page, custom, content, section, layout, file'
                }
            });
            $(picker).on('Result', function(e, ldata){
                if(!ldata) {
                    return
                }
                var url = ldata.url,
                    target = ldata.target,
                    name = ldata.text,
                    data = ldata.object;

                if(scope.nodeName === 'INPUT'){
                    scope.value = url;
                    $(scope).trigger('change')
                } else{
                    if(scope.dataset.for){
                        var field = $('#' + scope.dataset.for);
                        field.val(url);
                        var parent = mw.tools.firstParentWithClass(this, 'mw-ui-gbox');
                        fields = mw.$('[name="content_id"], [name="categories_id"]', parent).val('0');
                        // fields.attr('type', 'text');
                        if(data){
                            if(data.type === 'page'){
                                fields.filter('[name="content_id"]', parent).val(data.id)
                            } else if(data.type === 'category') {
                                fields.filter('[name="categories_id"]').val(data.id);
                            }
                        }
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
