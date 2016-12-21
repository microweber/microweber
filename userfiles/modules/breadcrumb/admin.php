<div class="module-live-edit-settings">

<?php $selected_start_depth =  get_option('data-start-from', $params['id']); ?>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">
          <?php _e("Start level"); ?>
        </label>
        <select name="data-start-from"   class="mw-ui-field mw_option_field" >
        
          <option  value=''  <?php if('' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>>
          <?php _e("Default"); ?>
          </option>
        
          <option  value='page'  <?php if('page' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>>
          <?php _e("Page"); ?>
          </option>
          <?php ?>
        <option  value='category'  <?php if('category' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>>
          <?php _e("Category"); ?>
          </option>
          <?php ?>
      
        </select>
      </div>
      
      
      <hr />




    <module type="admin/modules/templates"/>
</div>