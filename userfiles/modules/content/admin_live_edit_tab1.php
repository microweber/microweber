<?php
only_admin_access();

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

        $(mwd.body).ajaxStop(function () {
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
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Display"); ?> <?php print ($set_content_type) ?> <?php _e("from page"); ?></label>


        <select name="data-page-id" id="the_post_data-page-id<?php print  $rand ?>" class="mw-ui-field w100 mw_option_field" onchange="mw_reload_content_mod_window()">

            <?php if(intval($posts_parent_page) > 0 and !get_content_by_id($posts_parent_page)){ ?>
                <option value=""   selected="selected"  >
                    <?php _e("Unknown page"); ?>
                </option>
            <?php } ?>
            <option value="current_page" <?php if (('current_page' == ($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>>
                --
                <?php _e("Current page"); ?>
            </option>
            <option value="0" <?php if ($posts_parent_page != 'current_page' and (0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>>
                <?php _e("All pages"); ?>
            </option>
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

        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e("Show only from category"); ?></label>
            <select name="data-category-id" id="the_post_data-page-id<?php print  $rand ?>"
                    class="mw-ui-field w100 mw_option_field" data-also-reload="<?php print  $config['the_module'] ?>">
                <option value='' <?php if ((0 == intval($posts_parent_category))): ?>   selected="selected"  <?php endif; ?>>
                    <?php _e("Select a category"); ?>
                </option>
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
                <option value='0' <?php if ((0 == intval($posts_parent_category))): ?>   selected="selected"  <?php endif; ?>>
                    --
                    <?php _e("All"); ?>
                </option>
                <option value='related' <?php if (('related' == trim($posts_parent_category))): ?>   selected="selected"  <?php endif; ?>>
                    --
                    <?php _e("Related"); ?>
                </option>
                <option value='sub_pages' <?php if (('sub_pages' == trim($posts_parent_category))): ?>   selected="selected"  <?php endif; ?>>
                    --
                    <?php _e("Sub Pages"); ?>
                </option>
            </select>
        </div>
    <?php endif; ?>

    <div class="bootstrap3ns">
        <?php
        $all_existing_tags = json_encode(content_tags());
        if ($all_existing_tags == null) {
            $all_existing_tags = '[]';
        }
        //print_r(content_tags());
        ?>

        <div class="mw-ui-field-holder">
            <?php
            $tags_val = get_option('data-tags', $params['id']);
            if ($tags_val and is_string($tags_val)) {
                $tags_val = explode(',', $tags_val);
                $tags_val = array_trim($tags_val);
                $tags_val = array_filter($tags_val);
                $tags_val = array_unique($tags_val);
                $tags_val = implode(',', $tags_val);
            }
            ?>

            <div class="form-group">
                <label class="control-label" for="tags"><?php _e("Show content with tags"); ?></label>
                <input type="text" name="data-tags" class="form-control mw-full-width mw_option_field " value="<?php print $tags_val ?>" data-role="tagsinput" id="tags"/>
            </div>

            <script>
                $(document).ready(function () {
                    var data = <?php print $all_existing_tags ?>;

                    var tags = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.whitespace,
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        local: data
                    });
                    tags.initialize();

                    $('input[name="data-tags"]').tagsinput({
                        allowDuplicates: false,
                        typeaheadjs: {
                            name: "tags",
                            source: tags.ttAdapter()
                        },
                        freeInput: true
                    });
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

    <style type="text/css">
        .mw-ui-row-nodrop {
            width: 100%;
            padding: 10px;
        }

        .admin-manage-toolbar-content .mw-ui-row-nodrop {
            padding-left: 0;
            padding-right: 0;
        }

        .mw-ui-row-nodrop:hover {
            background-color: #f5f5f5;
        }

        .mw-ui-row-nodrop .mw-ui-col:last-child {
            text-align: right;
            vertical-align: middle;
        }

        .mw-ui-row-nodrop, .mw-ui-row-nodrop *, .mw-ui-row-nodrop .mw-ui-col {
            vertical-align: middle;
        }
    </style>

    <label class="mw-ui-label"><?php _e("Display on") . ' '; ?> <?php print ($set_content_type) ?>: </label>

    <div class="">
        <ul id="post_fields_sort_<?php print  $rand ?>" class="fields-controlls">
            <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col">
                    <label class="mw-ui-check">
                        <input type="checkbox" name="data-show" value="thumbnail" class="mw_option_field" <?php if (in_array('thumbnail', $show_fields)): ?>   checked="checked"  <?php endif; ?> />
                        <span></span> <span><?php _e("Thumbnail"); ?></span>
                    </label>
                </div>

                <div class="mw-ui-col">
                    <?php

                    /*
                      <label class="mw-ui-label-horizontal"><?php _e("Size"); ?></label>
                      <input name="data-thumbnail-size" class="mw-ui-field mw_option_field"   type="text" style="width:95px;" placeholder="250x200"  value="<?php print get_option('data-thumbnail-size', $params['id']) ?>" />
                     */

                    ?>
                </div>
            </div>

            <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col">
                    <label class="mw-ui-check">
                        <input type="checkbox" name="data-show" value="title" class="mw_option_field" <?php if (in_array('title', $show_fields)): ?>   checked="checked"  <?php endif; ?> />
                        <span></span> <span><?php _e("Title"); ?></span>
                    </label>
                </div>
                <div class="mw-ui-col">
                    <label class="mw-ui-label-horizontal"><?php _e("Length"); ?></label>
                    <input name="data-title-limit" class="mw-ui-field mw_option_field" type="text" placeholder="255" style="width:95px;" value="<?php print get_option('data-title-limit', $params['id']) ?>"/>
                </div>
            </div>

            <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col">
                    <label class="mw-ui-check">
                        <input type="checkbox" name="data-show" value="description" class="mw_option_field" <?php if (in_array('description', $show_fields)): ?>   checked="checked"  <?php endif; ?> />
                        <span></span> <span><?php _e("Description"); ?></span>
                    </label>
                </div>

                <div class="mw-ui-col">
                    <label class="mw-ui-label-horizontal"><?php _e("Length"); ?></label>
                    <input name="data-character-limit" class="mw-ui-field mw_option_field" type="text" placeholder="80" style="width:95px;" value="<?php print get_option('data-character-limit', $params['id']) ?>"/>
                </div>
            </div>

            <?php if ($is_shop): ?>
                <div class="mw-ui-row-nodrop">
                    <div class="mw-ui-col">
                        <label class="mw-ui-check">
                            <input type="checkbox" name="data-show" value="price" class="mw_option_field" <?php if (in_array('price', $show_fields)): ?>   checked="checked"  <?php endif; ?> />
                            <span></span> <span><?php _e("Show price"); ?></span>
                        </label>
                    </div>
                    <div class="mw-ui-col"></div>
                </div>

                <div class="mw-ui-row-nodrop">
                    <div class="mw-ui-col">
                        <label class="mw-ui-check">
                            <input type="checkbox" name="data-show" value="add_to_cart" class="mw_option_field" <?php if (in_array('add_to_cart', $show_fields)): ?>   checked="checked"  <?php endif; ?> />
                            <span></span> <span><?php _e("Add to cart button"); ?></span>
                        </label>
                    </div>

                    <div class="mw-ui-col">
                        <label class="mw-ui-label-horizontal"><?php _e("Title"); ?></label>
                        <input name="data-add-to-cart-text" class="mw-ui-field mw_option_field" style="width:95px;" placeholder="<?php _e("Add to cart"); ?>" type="text" value="<?php print get_option('data-add-to-cart-text', $params['id']) ?>"/>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col">
                    <label class="mw-ui-check">
                        <input type="checkbox" name="data-show" value="read_more" class="mw_option_field" <?php if (in_array('read_more', $show_fields)): ?>   checked="checked"  <?php endif; ?> />
                        <span></span> <span><?php _e("Read More Link"); ?></span>
                    </label>
                </div>

                <div class="mw-ui-col">
                    <label class="mw-ui-label-horizontal"><?php _e("Title"); ?></label>
                    <input name="data-read-more-text" class="mw-ui-field mw_option_field" type="text" placeholder="<?php _e("Read more"); ?>" style="width:95px;" value="<?php print get_option('data-read-more-text', $params['id']) ?>"/>
                </div>
            </div>

            <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col">
                    <label class="mw-ui-check">
                        <input type="checkbox" name="data-show" value="created_at" class="mw_option_field" <?php if (in_array('created_at', $show_fields)): ?>   checked="checked"  <?php endif; ?> />
                        <span></span> <span><?php _e("Date"); ?></span>
                    </label>
                </div>
                <div class="mw-ui-col"></div>
            </div>

            <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col">
                    <label class="mw-ui-check">
                        <input type="checkbox" name="data-hide-paging" value="y" class="mw_option_field" <?php if (get_option('data-hide-paging', $params['id']) == 'y'): ?>   checked="checked"  <?php endif; ?> />
                        <span></span><span><?php _e("Hide paging"); ?></span>
                    </label>
                </div>
                <div class="mw-ui-col">
                    <label class="mw-ui-label-horizontal"><?php _e("Posts per page"); ?></label>
                    <input name="data-limit" class="mw-ui-field mw_option_field right" type="number" style="width:55px;" placeholder="10" value="<?php print get_option('data-limit', $params['id']) ?>"/>
                </div>
            </div>

            <div class="mw-ui-row-nodrop">
                <label class="mw-ui-label-horizontal">
                    <?php $ord_by = get_option('data-order-by', $params['id']); ?>
                    <span></span><span><?php _e("Order by"); ?></span>
                </label>

                <div class="mw-ui-col">
                    <select name="data-order-by" class="mw-ui-field w100 mw_option_field" data-also-reload="<?php print  $config['the_module'] ?>">
                        <option value="" <?php if ((0 == intval($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Position"); ?>(ASC)</option>
                        <option value="position asc" <?php if (('position asc' == trim($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Position"); ?>(DESC)</option>
                        <option value="created_at desc" <?php if (('created_at desc' == trim($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Date"); ?>(ASC)</option>
                        <option value="created_at asc" <?php if (('created_at asc' == trim($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Date"); ?>(DESC)</option>
                        <option value="title asc" <?php if (('title asc' == trim($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Title"); ?>(ASC)</option>
                        <option value="title desc" <?php if (('title desc' == trim($ord_by))): ?>   selected="selected"  <?php endif; ?>><?php _e("Title"); ?>(DESC)</option>
                    </select>
                </div>
            </div>
        </ul>
    </div>
<?php endif; ?>