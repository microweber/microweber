



<div  <?php if(isset($params['backend'])): ?>       class="mw-module-admin-wrap"<?php endif; ?>>

    <?php if(isset($params['backend'])): ?>
        <module type="admin/modules/info" />
    <?php endif; ?>

    <script>


        $(document).ready(function () {
            mw.$("#post_select").on("focus", function () {
                document.getElementById('display_from_post').checked = true;
            });
        });


    </script>
    <style>

        .tab{
            display: none;
        }

    </style>
    <?php


    $get_comments_params = array();
    $get_comments_params['rel_type'] = 'content';
    if (isset($params['content-id'])) {

        $get_comments_params['rel_id'] = $params['content-id'];
    }

    if (!isset($get_comments_params['rel_id'])) {


        if (defined('POST_ID') == true and intval(POST_ID) != 0) {
            $get_comments_params['rel_id'] = POST_ID;
        }
    }
    if (!isset($get_comments_params['rel_id'])) {
        if (defined('PAGE_ID') == true) {
            $get_comments_params['rel_id'] = PAGE_ID;
        }
    }
    if (!isset($get_comments_params['rel_id'])) {

        $get_comments_params['rel_id'] = $params['id'];

    }


    if (isset($params['backend']) == true): ?>
        <?php include(__DIR__.DS.'backend.php'); ?>
    <?php else : ?>
        <script>

            $(document).ready(function(){
                mw.tabs({
                    nav: ".mw-ui-btn-nav-tabs a",
                    tabs: ".tab"
                });
            });

        </script>
        <div class="module-live-edit-settings">
            <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs"> <a class="mw-ui-btn active" href="javascript:;">
                    <?php _e("New Comments"); ?>
                </a> <a class="mw-ui-btn" href="javascript:;">
                    <?php _e("Skin/Template"); ?>
                </a> <a class="mw-ui-btn" href="javascript:;" class="">
                    <?php _e("Settings"); ?>
                </a> </div>
            <div class="mw-ui-box mw-ui-box-content">
                <div class="tab" style="display: block">
                    <?php
                    $get_comments_params['count'] = '1';
                   // $get_comments_params['is_new'] = 1;
                    ?>
                    <?php $new = get_comments($get_comments_params); ?>
                    <?php if ($new > 0) { ?>
                        <?php if ($new == 1) { ?>
                            <h2 class="relative">
                                <?php _e("You have one new comment"); ?>
                                &nbsp;<span class="comments_number"><?php print $new; ?></span></h2>
                        <?php } else { ?>
                            <h2 class="relative">


                                <?php _e("Comments"); ?>:
                                &nbsp;<span class="comments_number"><?php print $new; ?></span></h2>
                        <?php } ?>
                        <a href="<?php print admin_url('view:comments'); ?>/#content_id=<?php print  $get_comments_params['rel_id']; ?>"
                           target="_top" class="mw-ui-btn mw-ui-btn-green right">
                            <?php _e("See all"); ?>
                        </a>
                    <?php } else { ?>
                        <?php
                        unset($get_comments_params['is_moderated']);
                        $old = get_comments($get_comments_params);



                        ?>
                        <h2 class="relative inline-block pull-left">
                            <?php _e("You don't have new comments"); ?>
                        </h2>
                        <a href="<?php print admin_url('view:comments'); ?>/#content_id=<?php print  $get_comments_params['rel_id']; ?>"
                           target="_top" class="mw-ui-btn pull-right" style="top:6px;">
                            <?php _e("See all"); ?>
                            <strong><?php print $old; ?></strong></a>
                    <?php } ?>
                </div>
                <div class="tab">
                    <module type="admin/modules/templates"/>
                </div>
                <div class="tab">
                    <?php $display_comments_from = get_option('display_comments_from', $params['id']); ?>
                    <?php $display_comments_from_which_post = get_option('display_comments_from_which_post', $params['id']); ?>
                    <label class="mw-ui-label">
                        <?php _e("Display comments from"); ?>
                    </label>
                    <div class="mw-ui-field-holder checkbox-plus-select">
                        <label class="mw-ui-check">
                            <input name="display_comments_from" class="mw_option_field" value="current" id="display_from_post"
                                   type="radio" <?php if ($display_comments_from == 'current'): ?>  checked="checked" <?php endif ?> />
                            <span></span> </label>
                        <select name="display_comments_from_which_post" id="post_select" class="mw-ui-field mw_option_field">
                            <option
                                value="current_post" <?php if ($display_comments_from_which_post == 'current_post'): ?> selected="selected" <?php endif ?>>
                                <?php _e("Current Post"); ?>
                            </option>
                            <?php $posts = get_posts("is_active=1&limit=1000"); $html = ''; ?>


                            <?php if(is_array($posts)): ?>
                                <?php
                                foreach ($posts as $post) {
                                    $sel_html = '';
                                    if ($display_comments_from_which_post == $post['id']) {
                                        $sel_html = ' selected="selected" ';
                                    }
                                    $html .= '<option value="' . $post['id'] . '">' . $post['title'] . '</option>';
                                }
                                print $html;
                                ?>


                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-check">
                            <input name="display_comments_from" class="mw_option_field" type="radio"
                                   value="recent" <?php if ($display_comments_from == 'recent'): ?>  checked="checked" <?php endif ?> />
                            <span></span> <span>
            <?php _e("Recent comments"); ?>
            </span> </label>
                    </div>
                    <div class="mw-ui-field-holder" style="padding-bottom: 20px;">
                        <label class="mw-ui-check left">
                            <input name="display_comments_from" class="mw_option_field" type="radio"
                                   value="module" <?php if ($display_comments_from == 'module'): ?>  checked="checked" <?php endif ?> />
                            <span></span> <span>
            <?php _e("Custom comments"); ?>
            </span></label>
                        <a class="left ico iplus" href="javascript:$('#custom_comm_toggle').toggle(); void(0);"></a></div>
                    <div class="mw-ui-field-holder" id="custom_comm_toggle" style="display:none; margin-top:5px;">
                        <label class="mw-ui-inline-label">
                            <?php _e("From module"); ?>
                            : </label>
                        <?php
                        $comment_modules = array();
                        $comment_modules['rel_type'] = 'modules';
                        $comment_modules['rel_id'] = '[not_null]';
                        $comment_modules['fields'] = 'rel_type,rel_id';

                        $comment_modules['group_by'] = 'rel_type,rel_id';
                        $comment_modules['limit'] = '200';


                        // $comment_modules['debug'] =  'modules';
                        $comment_modules = get_comments($comment_modules);


                        $comments_module_select = array();


                        if (is_array($comment_modules)): ?>
                            <?php foreach ($comment_modules as $item): ?>
                                <?php $comment_module_title = get_option('form_title', $item['rel_id']); ?>
                                <?php // d( $comment_module_title); ?>
                                <?php if ($comment_module_title != false and trim($comment_module_title) != '') {
                                    $comments_module_select[$item['rel_id']] = $comment_module_title;
                                }
                                ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php   $curent_val = get_option('module_id', $params['id']); ?>
                        <?php if (is_array($comments_module_select)): ?>
                            <select name="module_id" class="mw_option_field mw-ui-field" type="text" parent-reload="true">
                                <option
                                    value="<?php print $params['id'] ?>" <?php if ($curent_val == $params['id']): ?> selected="selected" <?php endif; ?>>
                                    <?php _e("This module"); ?>
                                </option>
                                <?php foreach ($comments_module_select as $key => $item): ?>
                                    <?php if ($key != $params['id']): ?>
                                        <option
                                            value="<?php print $key ?>" <?php if ($curent_val == $key): ?> selected="selected" <?php endif; ?>>
                                            <?php if ($key == $params['id']): ?>
                                                (
                                                <?php _e("This module"); ?>
                                                )
                                            <?php endif; ?>
                                            <?php print $item ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        <?php else : ?>
                            <input type="text" parent-reload="true" placeholder="<?php print $params['id'] ?>"
                                   class="mw-ui-field mw_option_field" name="module_id"
                                   value="<?php print get_option('module_id', $params['id']) ?>"/>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <div class="mw-ui-row-nodrop">
                        <div class="mw-ui-col">
                            <label class="mw-ui-check">
                                <?php  $enable_comments_paging = get_option('enable_comments_paging', $params['id']) == 'y';  ?>
                                <input type="checkbox"   <?php if ($enable_comments_paging): ?>   checked="checked"  <?php endif; ?>
                                       value="y" name="enable_comments_paging" class="mw_option_field" parent-reload="true"/>
                                <span></span> <span>
              <?php _e("Show paging"); ?>
              </span> </label>
                        </div>
                        <div class="mw-ui-col">
                            <label class="mw-ui-inline-label">
                                <?php _e("Comments per page"); ?>
                            </label>
                            <input type="text" placeholder="10" style="width:32px;" class="mw-ui-field mw-ui-field-medium mw_option_field"
                                   name="comments_per_page" value="<?php print get_option('comments_per_page', $params['id']) ?>"
                                   parent-reload="true"/>
                        </div>
                    </div>
                    <label class="mw-ui-label">
                        <?php _e("Form title"); ?>
                    </label>
                    <input type="text" placeholder="<?php _e("Use default"); ?>" class="mw-ui-field w100 mw_option_field" name="form_title"
                           value="<?php print get_option('form_title', $params['id']) ?>" parent-reload="true"/>
                    <?php  $are_enabled = get_option('enable_comments', 'comments') == 'y';  ?>
                    <?php if (!$are_enabled): ?>
                        <label class="mw-ui-check">
                            <?php  $are_enabled = get_option('enable_comments', 'comments') == 'y';  ?>
                            <input
                                type="checkbox"
                                name="enable_comments"
                                value="y"
                                class="mw_option_field"
                                option-group="comments"
                                <?php if ($are_enabled): ?>  checked="checked" <?php endif; ?>
                            />
                            <span></span> <span>
          <?php _e("Enable comments"); ?>
                                ?</span> </label>
                    <?php endif; ?>
                    <?php  $are_disabled = get_option('disable_new_comments', $params['id']) == 'y';  ?>
                    <div class="mw-ui-field-holder" >
                        <label class="mw-ui-check">
                            <input
                                type="checkbox"
                                name="disable_new_comments"
                                value="y"
                                class="mw_option_field"

                                <?php if ($are_disabled): ?>   checked="checked"  <?php endif; ?>
                            />
                            <span></span> <span>
            <?php _e("Disable posting of new comments"); ?>
                                ?</span> </label>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
