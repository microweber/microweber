<?php if(!isset($data)){
$data = $params;	
}

?>

<div class="mw-admin-edit-content-holder-default">
  <div id="content-edit-settings-tabs-holder">
    <div id="content-edit-settings-tabs">
      <div id="quick-add-post-options-holder">
        <div class="mw-ui-btn-nav" id="quick-add-post-options"> <span class="mw-ui-btn"><span class="mw-icon-picture"></span><span>
          <?php _e("Picture Gallery"); ?>
          </span></span>
          <?php if($data['content_type'] == 'page'): ?>
          <span class="mw-ui-btn"><span class="mw-icon-navigation"></span><span>
          <?php _e('Add to navigation menu'); ?>
          </span> </span>
          <?php endif; ?>
          <?php  if($data['subtype']== 'product'): ?>
          <span class="mw-ui-btn"><span class="mw-icon-pricefields"></span><span>
          <?php _e("Price & Fields"); ?>
          </span></span> <span class="mw-ui-btn"><span class="mw-icon-truck"></span><span>
          <?php _e("Shipping & Options"); ?>
          </span></span>
          <?php else: ?>
          <span class="mw-ui-btn"><span class="mw-icon-fields"></span><span>
          <?php _e("Custom Fields"); ?>
          </span></span>
          <?php endif; ?>
          <span class="mw-ui-btn"><span class="mw-icon-gear"></span><span>
          <?php _e("Advanced"); ?>
          </span></span>
          <?php if($data['content_type'] == 'page'):  ?>
          <span class="mw-ui-btn"><span class="mw-icon-template"></span><span>
          <?php _e("Template"); ?>
          </span></span>
          <?php endif; ?>
          <?php event_trigger('mw_admin_edit_page_tabs_nav', $data); ?>
        </div>
      </div>
      <div id="quick-add-post-options-items-holder" class="tip-box"> <span class="mw-tooltip-arrow"></span>
        <div id="quick-add-post-options-items-holder-container">
          <div class="quick-add-post-options-item" id="quick-add-gallery-items">
            <module type="pictures/admin" for="content" for-id=<?php print $data['id']; ?> />
            <?php event_trigger('mw_admin_edit_page_after_pictures', $data); ?>
            <?php event_trigger('mw_admin_edit_page_tab_1', $data); ?>
          </div>
          <?php if($data['content_type'] == 'page'): ?>
          <div class="quick-add-post-options-item">
            <?php event_trigger('mw_edit_page_admin_menus', $data); ?>
            <?php event_trigger('mw_admin_edit_page_after_menus', $data); ?>
            <?php event_trigger('mw_admin_edit_page_tab_2', $data); ?>
          </div>
          <?php endif; ?>
          <div class="quick-add-post-options-item">
            <module
                    type="custom_fields/admin"
                    <?php if( trim($data['subtype']) == 'product' ): ?> default-fields="price" <?php endif; ?>
                    content-id="<?php print $data['id'] ?>"
                    suggest-from-related="true"
                    list-preview="true"
                    id="fields_for_post_<?php print $data['id']; ?>" 	 />
            <?php event_trigger('mw_admin_edit_page_tab_3', $data); ?>
          </div>
          <?php  if(trim($data['subtype']) == 'product'): ?>
          <div class="quick-add-post-options-item">
            <?php event_trigger('mw_edit_product_admin', $data); ?>
          </div>
          <?php endif; ?>
          <div class="quick-add-post-options-item" id="quick-add-post-options-item-advanced">
            <?php event_trigger('mw_admin_edit_page_tab_4', $data); ?>
            <module type="content/advanced_settings" content-id="<?php print $data['id']; ?>"  content-type="<?php print $data['content_type']; ?>" subtype="<?php print $data['subtype']; ?>"    />
          </div>
          <?php if($data['content_type'] == 'page'):  ?>
          <div class="quick-add-post-options-item quick-add-content-template" id="quick-add-post-options-item-template">
            <module type="content/layout_selector" id="mw-quick-add-choose-layout" autoload="yes" template-selector-position="bottom" content-id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>" />
          </div>
          <?php endif; ?>
          <?php event_trigger('mw_admin_edit_page_tabs_end', $data); ?>
        </div>
      </div>
    </div>
  </div>
</div>
