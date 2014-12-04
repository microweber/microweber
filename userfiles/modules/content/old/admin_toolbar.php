<?php
    $type = 'page';
    $act = url_param('action', 1);
?>
<?php
$last_page_front = session_get('last_content_id');
if ($last_page_front == false) {
    if (isset($_COOKIE['last_page'])) {
        $last_page_front = $_COOKIE['last_page'];
    }
}
if ($last_page_front != false) {
    $cont_by_url = mw()->content_manager->get_by_id($last_page_front, true);
    if (isset($cont_by_url) and $cont_by_url == false) {
        $past_page = get_content("order_by=updated_at desc&limit=1");
        $past_page = mw()->content_manager->link($past_page[0]['id']);
    } else {
        $past_page = mw()->content_manager->link($last_page_front);
    }
}
else {
    $past_page = get_content("order_by=updated_at desc&limit=1");
    $past_page = mw()->content_manager->link($past_page[0]['id']);
}
?>
<div class="admin-manage-toolbar-holder">
  <div class="admin-manage-toolbar">
    <div class="admin-manage-toolbar-content">
      <?php if(!isset($edit_page_info)): ?>
      <div class="mw-ui-row" style="width: 100%;">
        <div class="mw-ui-col">
          <div class="mw-ui-row" style="width: 100%;padding-top: 19px;">
            <div class="mw-ui-col">
              <?php if(isset($page_info) and is_array($page_info)): ?>
              <?php if($page_info['is_shop'] == 'y'){ $type='shop'; } elseif($page_info['subtype'] == 'dynamic'){ $type='dynamicpage'; } else{ $type='page';  }; ?>
              <h2><span class="mw-icon-<?php print $type; ?>"></span><?php print ($page_info['title']) ?></h2>
              <?php /*<a href="<?php print page_link($page_info['id']) ?>">
              <?php _e("Link"); ?>
              </a>*/ ?>
              <?php elseif($act == 'pages'): ?>
              <h2><span class="mw-icon-website"></span>
                <?php _e("Pages"); ?>
              </h2>
              <?php elseif($act == 'posts'): ?>
              <h2><span class="mw-icon-website"></span>
                <?php _e("Posts"); ?>
              </h2>
              <?php elseif($act == 'products'): ?>
              <h2><span class="mw-icon-website"></span>
                <?php _e("Products"); ?>
              </h2>
              <?php else: ?>
              <h2><span class="mw-icon-website"></span>
                <?php _e("Website"); ?>
              </h2>
              <?php endif; ?>
            </div>
            <div class="mw-ui-col">
              <div class="manage-toobar ">
                <div class="manage-toobar-content">
                  <div class="mw-ui-btn-nav pull-right">
                    <?php if(isset($params['page-id']) and intval($params['page-id']) != 0): ?>
                    <?php $edit_link = admin_url('view:content#action=editpost:'.$params['page-id']);  ?>
                    <?php endif; ?>
                    <?php if(isset($params['category-id'])): ?>
                    <?php $edit_link = admin_url('view:content#action=editcategory:'.$params['category-id']);  ?>
                    <?php endif; ?>
                    <?php if(isset($params['page-id']) and intval($params['page-id']) != 0): ?>
                    <?php $edit_link = admin_url('view:content#action=editpost:'.$params['page-id']);  ?>
                    <a href="<?php print $edit_link; ?>" class="mw-ui-btn edit-content-btn" id="edit-content-btn" data-tip="bottom-left"><span class="mw-icon-pen"></span>
                    <?php _e("Edit page"); ?>
                    </a>
                    <?php endif; ?>
                    <?php if(isset($params['category-id'])): ?>
                    <?php $edit_link = admin_url('view:content#action=editcategory:'.$params['category-id']);  ?>
                    <a href="<?php print $edit_link; ?>" class="mw-ui-btn edit-category-btn" id="edit-category-btn" data-tip="bottom-left"> <span class="mw-icon-pen"></span>
                    <?php _e("Edit category"); ?>
                    </a>
                    <?php endif; ?>
                  </div>
                  <input
                      onkeyup="mw.on.stopWriting(this,function(){mw.url.windowHashParam('search',this.value)})"
                      value="<?php  if(isset($params['keyword']) and $params['keyword'] != false):  ?><?php print $params['keyword'] ?><?php endif; ?>"
                      placeholder="<?php _e("Search for posts"); ?>"
                      type="text"
                      style="margin-right: 10px;max-width: 145px;"
                      class="mw-ui-searchfield pull-right"
                      id="mw-search-field" />
                </div>
              </div>
            </div>
            <div class="mw-ui-col col-bar-live-edit"> <a href="<?php print $past_page; ?>?editmode=y" class="mw-ui-btn default-invert tip" data-tip="<?php _e("Go Live Edit"); ?>" data-tipposition="bottom-center"><span class="mw-icon-live"></span></a> </div>
          </div>
          <?php else: ?>
          <div class="mw-ui-row">
            <div class="mw-ui-col">
              <?php
			    if($edit_page_info['is_shop'] == 'y'){
			        $type='shop';
                }
                elseif($edit_page_info['subtype'] == 'dynamic'){
                  $type='dynamicpage';
                }
                elseif($edit_page_info['subtype'] == 'post'){
                  $type='post';
                }
                elseif($edit_page_info['subtype'] == 'product'){
                  $type='product';
                }
                else{
                  $type='page';
                };
            	 $action_text =  _e("Creating new", true);
            	 if(isset($edit_page_info['id']) and intval($edit_page_info['id']) != 0){
            	    $action_text = _e("Editting", true);
            	 }
            	 $action_text2 = 'page';
            	 if(isset($edit_page_info['content_type']) and $edit_page_info['content_type'] == 'post' and isset($edit_page_info['subtype'])){
            	    $action_text2 = $edit_page_info['subtype'];
            	 }
            	 $action_text = $action_text. ' '. $action_text2;
			  if(isset($edit_page_info['title'])): ?>
              <div class="mw-ui-row" id="content-title-field-row">
                <div class="mw-ui-col" style="width: 30px;"><span class="mw-icon-<?php print $type; ?> admin-manage-toolbar-title-icon"></span></div>
                <div class="mw-ui-col">
                  <input type="text" class="mw-ui-invisible-field mw-ui-field-big" value="<?php print $edit_page_info['title'] ?>" id="content-title-field" <?php if($edit_page_info['title'] == false): ?> placeholder="<?php print $action_text ?>"  <?php endif; ?> />
                </div>
              </div>
              <script>mwd.getElementById('content-title-field').focus();</script>
              <?php else: ?>
              <?php if($edit_page_info['is_shop'] == 'y'){ $type='shop'; } elseif($edit_page_info['subtype'] == 'dynamic'){ $type='dynamicpage'; } else{ $type='page';  }; ?>
              <h2> <span class="mw-icon-<?php print $type; ?>"></span><?php print $action_text ?> </h2>
              <?php endif; ?>
            </div>
            <div class="mw-ui-col" id="content-title-field-buttons">
              <div class="mw-ui-btn-nav">
                <?php if($data['is_active'] == 'n'){ ?>
                <span
                onclick="mw.admin.postStates.toggle()"
                data-val="n"
                class="mw-ui-btn mw-ui-btn-icon btn-posts-state tip"
                data-tip="<?php _e("Unpublished"); ?>"
                data-tipposition="left-center"><span class="mw-icon-unpublish"></span> </span>
                <?php } else{  ?>
                <span
                onclick="mw.admin.postStates.toggle()"
                data-val="y"
                class="mw-ui-btn mw-ui-btn-icon btn-posts-state tip"
                data-tip="<?php _e("Published"); ?>"
                data-tipposition="left-center"><span class="mw-icon-check"></span> </span>
                <?php  } ?>
                <?php if($is_live_edit == false) : ?>
                <button type="button" class="mw-ui-btn" onclick="mw.edit_content.handle_form_submit(true);" data-text="<?php _e("Live Edit"); ?>"> <span class="mw-icon-live"></span>
                <?php _e("Live Edit"); ?>
                </button>
                <button type="submit" class="mw-ui-btn mw-ui-btn-invert" form="quickform-<?php print $rand; ?>">
                <?php _e("Save"); ?>
                </button>
                <?php else: ?>
                <?php if($data['id'] == 0): ?>
                <button type="submit" class="mw-ui-btn" onclick="mw.edit_content.handle_form_submit(true);" data-text="<?php _e("Live Edit"); ?>" form="quickform-<?php print $rand; ?>"> <span class="mw-icon-live"></span>
                <?php _e("Live Edit"); ?>
                </button>
                <?php else: ?>
                <button type="button" class="mw-ui-btn" onclick="mw.edit_content.handle_form_submit(true);" data-text="<?php _e("Live Edit"); ?>"> <span class="mw-icon-live"></span>
                <?php _e("Live Edit"); ?>
                </button>
                <?php endif; ?>
                <button type="submit" class="mw-ui-btn mw-ui-btn-invert" form="quickform-<?php print $rand; ?>">
                <?php _e("Save"); ?>
                </button>
                <?php endif; ?>
              </div>
            </div>
            <script>mw.admin.titleColumnNavWidth();</script>
            <?php endif; ?>
          </div>
        </div>
        <?php if(!isset($edit_page_info)): ?>
        <div class="manage-toobar manage-toolbar-top">
          <div class="manage-toobar-content">
            <div class="mw-ui-link-nav"> <span class="mw-ui-link" onclick="mw.check.all('#mw_admin_posts_manage')">
              <?php _e("Select All"); ?>
              </span> <span class="mw-ui-link" onclick="mw.check.none('#mw_admin_posts_manage')">
              <?php _e("Unselect All"); ?>
              </span> <span class="mw-ui-link" onclick="delete_selected_posts();">
              <?php _e("Delete"); ?>
              </span> </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
