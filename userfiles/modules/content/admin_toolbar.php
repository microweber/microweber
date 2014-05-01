<?php

    $type = 'page';

?>

<div class="admin-manage-toolbar-holder">
    <div class="admin-manage-toolbar">
    <div class="admin-manage-toolbar-content">
     <?php if(!isset($edit_page_info)): ?>
        <div class="mw-ui-row">
          <div class="mw-ui-col">
              <?php if(isset($page_info) and is_array($page_info)): ?>
              <?php if($page_info['is_shop'] == 'y'){ $type='shop'; } elseif($page_info['subtype'] == 'dynamic'){ $type='dynamicpage'; } else{ $type='page';  }; ?>
              <h2><span class="mw-icon-<?php print $type; ?>"></span><?php print ($page_info['title']) ?></h2>
              <?php else: ?>
              <h2><span class="mw-icon-website"></span>Website</h2>
              <?php endif; ?>
          </div>
          <div class="mw-ui-col" style="text-align: right">
              <span class="mw-ui-btn mw-ui-btn-medium create-content-btn" id="create-content-btn" data-tip="bottom-left"><span class="mw-icon-plus"></span> Create </span>
              <?php if(isset($params['page-id']) and intval($params['page-id']) != 0): ?>
              <?php $edit_link = admin_url('view:content#action=editpost:'.$params['page-id']);  ?>
              <a href="<?php print $edit_link; ?>" class="mw-ui-btn mw-ui-btn-medium edit-content-btn" id="edit-content-btn" data-tip="bottom-left"> <span class="mw-icon-pen"></span> Edit page </a>
              <?php endif; ?>
              <?php if(isset($params['category-id'])): ?>
              <?php $edit_link = admin_url('view:content#action=editcategory:'.$params['category-id']);  ?>
              <a href="<?php print $edit_link; ?>" class="mw-ui-btn mw-ui-btn-medium edit-category-btn" id="edit-category-btn" data-tip="bottom-left"> <span class="mw-icon-pen"></span> Edit category </a>
              <?php endif; ?> 
           </div>
           
    <?php else: ?>
    
    

     
     
         

        <div class="mw-ui-row">
            <div class="mw-ui-col">
            

             <?php  if(isset($edit_page_info['title']) and ($edit_page_info['title']) != ''): ?>
               <span class="mw-icon-<?php print $type; ?> admin-manage-toolbar-title-icon"></span>  <input type="text" class="mw-ui-invisible-field mw-ui-field-big"   value="<?php print $edit_page_info['title'] ?>" id="content-title-field">
              <script>mwd.getElementById('content-title-field').focus();</script>
              <?php else: ?>

                    <?php if($edit_page_info['is_shop'] == 'y'){ $type='shop'; } elseif($edit_page_info['subtype'] == 'dynamic'){ $type='dynamicpage'; } else{ $type='page';  }; ?>

        <?php
        	 $action_text =  _e("Creating new", true);
        	 if(isset($edit_page_info['id']) and intval($edit_page_info['id']) != 0){
        	 $action_text = _e("Editting", true);
        	 }
        	 $action_text2 = 'page';
        	 if(isset($edit_page_info['content_type']) and $edit_page_info['content_type'] == 'post' and isset($edit_page_info['subtype'])){
        	 $action_text2 = $edit_page_info['subtype'];
        	  }
        	 $action_text = $action_text. ' '. $action_text2;
        	 ?>
                  <h2>
               <span class="mw-icon-<?php print $type; ?>"></span>  <?php print $action_text ?>
        </h2>
              <?php endif; ?>

        
        
        </div>
            <div class="mw-ui-col" style="text-align: right">



                    <a onclick="window.history.back()" href="javascript:;" title=" <?php _e("Back"); ?>"> <span class="mw-icon-back"></span></a>
                    <a class="mw-ui-btn"><?php _e("Live Edit"); ?></a>
                    <a class="mw-ui-btn"><?php _e("Save"); ?></a>



            </div>
        </div>

      


      
      
	<?php endif; ?> 

    
    

    
    
        </div>
    </div>
    </div>
</div>
