<?php $menus = get_menus(); ?>
<?php $rand = uniqid(); ?>



<div class="card">
   <div class="card-body">
       <div class="card-header ps-0 pt-1 mb-0">
           <strong><?php _e("Add to Navigation"); ?></strong>
       </div>
       <div class="row pb-0 p-3">


           <div class="position-relative" id="menu-selector-item">
               <?php
               $content_id = false;
               $try_under_parent = false;
               $select_default_menu = false;
               $add_to_menu = false;

               if (isset($params['content_id'])) {
                   $content_id = $params['content_id'];
               }

               if ($content_id == false) {
                   if (isset($params['parent']) and $params['parent'] == 0) {
                       $select_default_menu = true;
                   }
               }

               if (isset($params['parent'])) {
                   $try_under_parent = true;
               }

               if (isset($params['add_to_menu'])) {
                   $add_to_menu = $params['add_to_menu'];
                   $select_default_menu = false;
                   $try_under_parent = true;
               }

               if (is_array($menus)): ?>
                   <div id="mw-menu-selector-list-<?php print $rand; ?>" class="mw-menu-selector-list">
                       <?php foreach ($menus as $item): ?>
                           <div class="form-group">
                               <div class="custom-control custom-checkbox my-2">
                                   <input id="menuid-<?php print $item['id'] ?>" class="form-check-input" name="add_content_to_menu[]" <?php if (is_in_menu($item['id'], $content_id) or ($select_default_menu == true and $item['title'] == 'header_menu') or ($add_to_menu == true and $item['title'] == $add_to_menu)): ?> checked="checked" <?php endif; ?> value="<?php print $item['id'] ?>"
                                          type="checkbox" <?php if (isset($item['title'])): ?>  data-menu-key="<?php print addslashes(strtolower(str_replace(' ', '_', $item['title']))); ?>" <?php endif; ?> >
                                   <label class="custom-control-label" for="menuid-<?php print $item['id'] ?>"><?php print ucwords(str_replace('_', ' ', $item['title'])) ?></label>
                               </div>
                           </div>
                       <?php endforeach; ?>
                   </div>

                   <?php if (($try_under_parent) != false): ?>
                       <input type="hidden" name="add_content_to_menu_auto_parent" value="1"/>
                   <?php endif; ?>
               <?php endif; ?>
           </div>

       </div>
   </div>
</div>
