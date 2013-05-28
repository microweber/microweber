<?php only_admin_access(); ?><script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("Language settings are saved"); ?>.");
    });
});
</script><div class="<?php print $config['module_class'] ?>">
<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    <?php _e("Website Language"); ?>
    <br>
    <small>
    <?php _e("You can set the default language for your website."); ?>
    </small> </label>
    
    
    
    
    
     <div class="mw-ui-select">
    
    <select  name="language" class="mw_option_field"    option-group="website" data-also-reload="settings/group/language_edit" >
      <?php
        $def_language = get_option('language','website');
		$all_langs = get_available_languages();
		if($def_language == false){
		$def_language = 'en';
		}
		 foreach($all_langs as $language){
          $found = false;
		  if( $def_language == $language){

			$found = 1;  
		  }
			   if( $found == false){
					print '<option value="'. $language .'">'. $language . '</option>';
			  } else {
					print '<option selected="selected" value="'. $language .'">'. $language . '</option>';

			  }
		 }
    ?>
    </select> </div>
    

    
  <?php


  /*
   <label class="mw-ui-label"><?php _e("Add new language"); ?> </label>
   <input  name="language" class="mw_option_field mw-ui-field"  type="text"   option-group="website" also-reload="#mw_lang_file_edit" />   */



   ?>
</div>




<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    <?php _e("Edit your language file"); ?>
    
    </label>
    
    
    <microweber module="settings/group/language_edit" id="mw_lang_file_edit" />
    
    
         
    
    
    
</div>