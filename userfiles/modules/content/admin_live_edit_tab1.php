<?php
must_have_access();

$set_content_type = 'post';
if (isset($params['global']) and $params['global'] != false) {
    $set_content_type = get_option('data-content-type', $params['id']);
}

$rand = uniqid(); ?>

    <script type="text/javascript">
        $(document).ready(function () {
            mw.lib.require('bootstrap3ns');
            mw.lib.require('bootstrap_tags');
        });

        function mw_reload_content_mod_window(curr_mod) {
            setTimeout(function () {
                mw.reload_module_parent('#<?php print $params['id'] ?>');
                window.location.href = mw.url.removeHash(window.location.href);
            }, 1000)

            $(document.body).ajaxStop(function () {
                setTimeout(function () {
                    window.location.href = mw.url.removeHash(window.location.href);
                }, 1000)
            });
        }
    </script>

<?php if (!isset($is_shop) or $is_shop == false): ?>
    <?php $is_shop = false;
    $pages = get_content('content_type=page&subtype=dynamic&is_shop=1&limit=1000'); ?>
<?php else: ?>
    <?php $pages = get_content('content_type=page&is_shop=0&limit=1000'); ?>
<?php endif; ?>

<?php $posts_parent_page = get_option('data-page-id', $params['id']); ?>

<?php if (isset($params['global']) and $params['global'] != false) : ?>
    <?php if ($set_content_type == 'product'): ?>
        <?php $is_shop = 1;
        $pages = get_content('content_type=page&is_shop=0&limit=1000'); ?>
    <?php endif; ?>

    <label class="mw-ui-label"><?php _e("Content type"); ?></label>
    <select name="data-content-type" id="the_post_data-content-type<?php print  $rand ?>" class="mw-ui-field w100 mw_option_field" onchange="mw_reload_content_mod_window(1)">
        <option value="" <?php if (('' == trim($set_content_type))): ?>  selected="selected"  <?php endif; ?>><?php _e("Choose content type"); ?></option>
        <option value="page" <?php if (('page' == trim($set_content_type))): ?>   selected="selected"  <?php endif; ?>><?php _e("Pages"); ?></option>
        <option value="post" <?php if (('post' == trim($set_content_type))): ?>   selected="selected"  <?php endif; ?>><?php _e("Posts"); ?></option>
        <option value="product" <?php if (('product' == trim($set_content_type))): ?>   selected="selected"  <?php endif; ?>><?php _e("Product"); ?></option>
        <option value="none" <?php if (('none' == trim($set_content_type))): ?>   selected="selected"  <?php endif; ?>><?php _e("None"); ?></option>
    </select>
<?php endif; ?>

<?php if (!isset($set_content_type) or $set_content_type != 'none') : ?>
    <div class="form-group">
        <label class="control-label d-block"><?php echo _e("Display", true) . ' ' . $set_content_type . ' ' . _e("from page", true); ?></label>

        <select name="data-page-id" id="the_post_data-page-id<?php print  $rand ?>" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true" onchange="mw_reload_content_mod_window()">
            <?php if (intval($posts_parent_page) > 0 and !get_content_by_id($posts_parent_page)) { ?>
                <option value="" selected="selected"><?php _e("Unknown page"); ?></option>
            <?php } ?>
            <option value="current_page" <?php if (('current_page' == ($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>>-- <?php _e("Current page"); ?></option>
            <option value="0" <?php if ($posts_parent_page != 'current_page' and (0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>><?php _e("All pages"); ?></option>
            <?php
            $pt_opts = array();
            $pt_opts['link'] = "{title}";
            //     $pt_opts['list_tag'] = "optgroup";
            //   $pt_opts['list_tag'] = " ";
            //   $pt_opts['list_item_tag'] = "option";

            $pt_opts['list_tag'] = " ";
            $pt_opts['list_item_tag'] = "option";

            //$pt_opts['include_categories'] = "option";
            $pt_opts['active_ids'] = $posts_parent_page;
            $pt_opts['remove_ids'] = $params['id'];
            $pt_opts['active_code_tag'] = '   selected="selected"  ';
            if ($is_shop != false) {
                $pt_opts['is_shop'] = 'y';
            }
            if ($set_content_type == 'product') {
                $pt_opts['is_shop'] = 'y';
            }

            pages_tree($pt_opts);
            ?>
        </select>
    </div>
    <?php if ($posts_parent_page != false and intval($posts_parent_page) > 0): ?>
        <?php $posts_parent_category = get_option('data-category-id', $params['id']); ?>

        <div class="form-group">
            <label class="control-label d-block"><?php _e("Show only from category"); ?></label>
            <select name="data-category-id" id="the_post_data-page-id<?php print  $rand ?>" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true" data-also-reload="<?php print  $config['the_module'] ?>">
                <option value='' <?php if ((0 == intval($posts_parent_category))): ?>   selected="selected"  <?php endif; ?>><?php _e("Select a category"); ?></option>
                <?php
                $pt_opts = array();
                $pt_opts['link'] = " {title}";

                $pt_opts['list_tag'] = " ";
                $pt_opts['list_item_tag'] = "option";

                //  $pt_opts['list_tag'] = " ";
                //   $pt_opts['list_tag'] = "optgroup";

                //  $pt_opts['list_item_tag'] = "option";
                $pt_opts['active_ids'] = $posts_parent_category;
                $pt_opts['active_code_tag'] = '   selected="selected"  ';
                $pt_opts['rel_type'] = 'content';
                $pt_opts['rel_id'] = $posts_parent_page;
                category_tree($pt_opts);
                ?>
                <option value='0' <?php if ((0 == intval($posts_parent_category))): ?>   selected="selected"  <?php endif; ?>>-- <?php _e("All"); ?></option>
                <option value='related' <?php if (('related' == trim($posts_parent_category))): ?>   selected="selected"  <?php endif; ?>>-- <?php _e("Related"); ?></option>
                <option value='sub_pages' <?php if (('sub_pages' == trim($posts_parent_category))): ?>   selected="selected"  <?php endif; ?>>-- <?php _e("Sub Pages"); ?></option>
                <option value='current_category' <?php if (('current_category' == trim($posts_parent_category))): ?>   selected="selected"  <?php endif; ?>>-- <?php _e("Current category"); ?></option>
            </select>
        </div>
    <?php endif; ?>

    <div class="bootstrap3ns">
        <?php
        $all_existing_tags = json_encode(content_tags());
        if ($all_existing_tags == null) {
            $all_existing_tags = '[]';
        }




         ?>


        <div>
            <?php
            $tags_val_arr = [];
            $tags_val = get_option('data-tags', $params['id']);
            if ($tags_val and is_string($tags_val)) {
                $tags_val = explode(',', $tags_val);
                $tags_val = array_trim($tags_val);
                $tags_val = array_filter($tags_val);
                $tags_val = array_filter($tags_val,'addslashes');
                $tags_val = array_unique($tags_val);
                $tags_val_arr = $tags_val;

                $tags_val = implode(',', $tags_val);
            }
            ?>



            <div class="form-group">
                <label class="control-label d-block"><?php _e("Show only with tags"); ?></label>


                <div class="col-12">
                    <div id="content-tags-block"></div>
                    <div id="content-tags-search-block"></div>
                    <input type="hidden" class="form-control mw-full-width mw_option_field"  name="data-tags"  value="<?php print $tags_val; ?>"  id="tags"/>
                </div>
            </div>

            <script type="text/javascript">


                $(document).ready(function () {
                    var data = <?php print json_encode($tags_val_arr) ?>;


                    var node = document.querySelector('#content-tags-block');
                    var nodesearch = document.querySelector('#content-tags-search-block');

                    var tagsData = <?php print  json_encode($tags_val_arr) ?>.map(function (tag){
                        return {title: tag, id: tag}
                    });
                    var tags = new mw.tags({
                        element: node,
                        data: tagsData,
                        color: 'primary',
                        size:  'sm',
                        inputField: false,
                    })
                    $(tags)
                        .on('change', function(e, item, data){
                            mw.element('[name="data-tags"]').val(data.map(function (c) {  return c.title }).join(',')).trigger('change')
                        });


                    var tagsSelect = mw.select({
                        element: nodesearch,
                        multiple: false,
                        autocomplete: true,
                        tags: false,
                        placeholder: '<?php _ejs('Add tag') ?>',
                        ajaxMode: {
                            paginationParam: 'page',
                            searchParam: 'keyword',
                            endpoint: mw.settings.api_url + 'tagging_tag/autocomplete',
                            method: 'get'
                        }
                    });


                    $(tagsSelect).on("change", function (event, tag) {
                        tags.addTag(tag)
                        setTimeout(function () {tagsSelect.element.querySelector('input').value = '';})
                    });

                    $(tagsSelect).on('enterOrComma', function (e, node){
                        tags.addTag({title: node.value, id: node.value});
                        setTimeout(function () {node.value = '';})
                    })



                });
            </script>








        </div>
    </div>

    <?php $show_fields = get_option('data-show', $params['id']);
    if (is_string($show_fields)) {
        $show_fields = explode(',', $show_fields);
        $show_fields = array_trim($show_fields);
    }
    if ($show_fields == false or !is_array($show_fields)) {
        $show_fields = array();
    }
    ?>

    <style >
        .setting-row {
            padding: 10px;
        }

        .setting-row .custom-control,
        .setting-row label {
            margin: 0;
        }

        .setting-row:hover {
            background-color: #f5f5f5;
        }
    </style>

    <label class="control-label"><?php _e("Display on") . ' '; ?> <?php print ($set_content_type) ?>:</label>



<script>

    $( document ).ready(function() {
        $('input[type=radio][name=showContentTypeRadioDefault]').change(function() {
            if (this.value == 'default') {
                $('#post_fields_show_toggle_fields').hide();

                $('[type=checkbox][name=data-show]').prop("checked", false).trigger('change');

            }
            else if (this.value == 'custom') {
                $('#post_fields_show_toggle_fields').show();
            }
        });
    });



</script>




    <div class="form-check">
        <input class="form-check-input" type="radio" name="showContentTypeRadioDefault" value="default" id="showContentTypeRadioDefault1" <?php if(empty($show_fields)): ?> checked <?php endif; ?> >
        <label class="form-check-label" for="showContentTypeRadioDefault1">
            <?php _e("Show default information from module skin"); ?>
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="showContentTypeRadioDefault" value="custom" id="showContentTypeRadioDefault2" <?php if(!empty($show_fields)): ?> checked <?php endif; ?>>
        <label class="form-check-label" for="showContentTypeRadioDefault2">
            <?php _e("Show custom information"); ?>
        </label>
    </div>







    <div id="post_fields_show_toggle_fields" <?php if(empty($show_fields)): ?> style="display:none;" <?php endif; ?>>
        <div id="post_fields_sort_<?php print  $rand ?>" class="fields-controlls">
            <div class="setting-row d-flex align-items-center justify-content-between">
                <div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input mw_option_field" name="data-show" value="thumbnail" id="thumbnail" <?php if (in_array('thumbnail', $show_fields)): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="thumbnail"><?php _e("Thumbnail"); ?></label>
                    </div>
                </div>
            </div>

            <div class="setting-row d-flex align-items-center justify-content-between">
                <div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input mw_option_field" name="data-show" value="title" id="title" <?php if (in_array('title', $show_fields)): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="title"><?php _e("Title"); ?></label>
                    </div>
                </div>

                <div>
                    <label class="d-inline-block"><?php _e("Length"); ?></label>
                    <input type="text" class="form-control d-inline-block w-auto mw_option_field" name="data-title-limit" value="<?php print get_option('data-title-limit', $params['id']) ?>" placeholder="255"/>
                </div>
            </div>

            <div class="setting-row d-flex align-items-center justify-content-between">
                <div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input mw_option_field" name="data-show" value="description" id="description" <?php if (in_array('description', $show_fields)): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="description"><?php _e("Description"); ?></label>
                    </div>
                </div>

                <div>
                    <label class="d-inline-block"><?php _e("Length"); ?></label>
                    <input type="text" class="form-control d-inline-block w-auto mw_option_field" name="data-character-limit" value="<?php print get_option('data-character-limit', $params['id']) ?>" placeholder="80"/>
                </div>
            </div>

            <?php if ($is_shop): ?>
                <div class="setting-row d-flex align-items-center justify-content-between">
                    <div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input mw_option_field" name="data-show" value="price" id="price" <?php if (in_array('price', $show_fields)): ?>checked<?php endif; ?>>
                            <label class="custom-control-label" for="price"><?php _e("Show price"); ?></label>
                        </div>
                    </div>
                </div>

                <div class="setting-row d-flex align-items-center justify-content-between">
                    <div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input mw_option_field" name="data-show" value="add_to_cart" id="add_to_cart" <?php if (in_array('add_to_cart', $show_fields)): ?>checked<?php endif; ?>>
                            <label class="custom-control-label" for="add_to_cart"><?php _e("Add to cart button"); ?></label>
                        </div>
                    </div>

                    <div>
                        <label class="d-inline-block"><?php _e("Title"); ?></label>
                        <input name="data-add-to-cart-text" class="mw_option_field form-control d-inline-block w-auto" style="width:95px;" placeholder="<?php _e("Add to cart"); ?>" type="text" value="<?php print get_option('data-add-to-cart-text', $params['id']) ?>"/>
                    </div>
                </div>

                <div class="setting-row d-flex align-items-center justify-content-between">
                    <div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input mw_option_field" name="filter-only-in-stock" value="1" id="filter-only-in-stock" <?php if (get_option('filter-only-in-stock', $params['id'])): ?>checked<?php endif; ?>>
                            <label class="custom-control-label" for="filter-only-in-stock"><?php _e("Show only products in stock"); ?></label>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="setting-row d-flex align-items-center justify-content-between">
                <div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input mw_option_field" name="data-show" value="read_more" id="read_more" <?php if (in_array('read_more', $show_fields)): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="read_more"><?php _e("Read More Link"); ?></label>
                    </div>
                </div>

                <div>
                    <label class="d-inline-block"><?php _e("Title"); ?></label>
                    <input name="data-read-more-text" class="mw_option_field form-control d-inline-block w-auto" type="text" placeholder="<?php _e("Read more"); ?>" style="width:95px;" value="<?php print get_option('data-read-more-text', $params['id']) ?>"/>
                </div>
            </div>
            <div class="setting-row d-flex align-items-center justify-content-between">
                <div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input mw_option_field" name="data-show" value="created_at" id="created_at" <?php if (in_array('created_at', $show_fields)): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="created_at"><?php _e("Date"); ?></label>
                    </div>
                </div>
            </div>

            <div class="setting-row d-flex align-items-center justify-content-between">
                <div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input mw_option_field" name="data-hide-paging" value="y" id="data-hide-paging" <?php if (get_option('data-hide-paging', $params['id']) == 'y'): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="data-hide-paging"><?php _e("Hide paging"); ?></label>
                    </div>
                </div>

                <div>
                    <label class="d-inline-block"><?php _e("Posts per page"); ?></label>
                    <input name="data-limit" class="mw_option_field form-control d-inline-block w-auto" type="number" style="width:55px;" placeholder="10" value="<?php print get_option('data-limit', $params['id']) ?>"/>
                </div>
            </div>

            <div class="setting-row d-flex align-items-center justify-content-between">
                <div></div>

                <div>
                    <label class="d-inline-block">
                        <?php $ord_by = get_option('data-order-by', $params['id']); ?>
                        <?php _e("Order by"); ?>
                    </label>

                    <select name="data-order-by" class="mw_option_field selectpicker" data-width="auto" data-also-reload="<?php print  $config['the_module'] ?>">
                        <option value="" <?php if ((0 == intval($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Position"); ?>(ASC)</option>
                        <option value="position asc" <?php if (('position asc' == trim($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Position"); ?>(DESC)</option>
                        <option value="created_at desc" <?php if (('created_at desc' == trim($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Date"); ?>(ASC)</option>
                        <option value="created_at asc" <?php if (('created_at asc' == trim($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Date"); ?>(DESC)</option>
                        <option value="title asc" <?php if (('title asc' == trim($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Title"); ?>(ASC)</option>
                        <option value="title desc" <?php if (('title desc' == trim($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Title"); ?>(DESC)</option>
                    </select>
                </div>
            </div>
        </div>



    </div>
<?php endif; ?>
