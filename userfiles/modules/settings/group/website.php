<?php only_admin_access(); ?>
<script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("All changes are saved"); ?>.");
    });
});
</script>

<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2><span class="mai-website"></span><?php _e("Website"); ?></h2>
    </div>
</div>
<div class="admin-side-content">
<div class="<?php print $config['module_class'] ?>">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Website Name"); ?>
      <br>
      <small>
      <?php _e("This is very important for search engines"); ?>
      .
      <?php _e("Your website will be categorized by many criteria and its name is one of them"); ?>
      . </small> </label>
    <input name="website_title" class="mw_option_field mw-ui-field" type="text" option-group="website"  value="<?php print get_option('website_title','website'); ?>" />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Website Description"); ?>
      <br>
      <small>
      <?php _e("Describe what your website is about"); ?>
      .</small> </label>
    <textarea name="website_description" class="mw_option_field mw-ui-field" type="text" option-group="website"><?php print get_option('website_description','website'); ?></textarea>
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Posts per Page"); ?>
      <br>
      <small>
      <?php _e("Select how many posts or products you want to be shown per page"); ?>
      ?</small> </label>
    <select  name="items_per_page" class="mw-ui-field mw_option_field" type="range" option-group="website" >
      <?php
        $per_page = get_option('items_per_page','website');
          $found = false;
          for($i=5; $i<40; $i+=5){
              if($i == $per_page){
                 $found = true;
                  print '<option selected="selected" value="'. $i .'">'. $i . '</option>';
              } else{
                  print '<option value="'. $i .'">'. $i . '</option>';
              }
          }
          if( $found == false){
                print '<option selected="selected" value="'. $per_page .'">'. $per_page . '</option>';
          }
    ?>
    </select>
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Website Keywords"); ?>
      <br>
      <small>
      <?php _e("Ex.: Cat, Videos of Cats, Funny Cats, Cat Pictures, Cat for Sale, Cat Products and Food"); ?>
      </small> </label>
    <input name="website_keywords" class="mw_option_field mw-ui-field"   type="text" option-group="website"  value="<?php print get_option('website_keywords','website'); ?>" />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Date Format"); ?>
    </label>
    <?php $date_formats = array("Y-m-d H:i:s","m/d/y", "m/d/Y","d/m/Y","F j, Y g:i a", "F j, Y", "F, Y", "l, F jS, Y", "M j, Y @ G:i", "Y/m/d \a\t g:i A", "Y/m/d \a\t g:ia", "Y/m/d g:i:s A", "Y/m/d", "g:i a", "g:i:s a" ,'D-M-Y','D-M-Y H:i');  ?>
    <?php   $curent_val = get_option('date_format','website'); ?>
    <select name="date_format" class="mw-ui-field mw_option_field" option-group="website" style="width:300px;">
      <?php if(is_array($date_formats )): ?>
      <?php foreach($date_formats  as $item): ?>
      <option value="<?php print $item ?>" <?php if($curent_val == $item): ?> selected="selected" <?php endif; ?>><?php print date($item, time())?> - (<?php print $item ?>)</option>
      <?php endforeach ; ?>
      <?php endif; ?>
    </select>
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Time Zone"); ?>
    </label>
    <?php   $curent_time_zone = get_option('time_zone','website'); ?>
    <?php 
 
 if( $curent_time_zone == false){
	 $curent_time_zone = date_default_timezone_get();
 }
 
 
  $timezones = timezone_identifiers_list(); ?>
    <select name="time_zone" class="mw-ui-field mw_option_field" option-group="website" style="width:300px;">
      <?php foreach ($timezones as $timezone) {
  echo '<option';
  if ( $timezone == $curent_time_zone ) echo ' selected="selected"';
  echo '>' . $timezone . '</option>' . "\n";
}?>
    </select>
  </div>
</div>




<script>

$(document).ready(function(){
    favUP = mw.uploader({
        element:mwd.getElementById('upload-icoimage'),
        filetypes:'images',
        multiple:false
    });

    $(favUP).bind('FileUploaded', function(a,b){
        mw.$("#favicon_image").val(b.src).trigger('change');
        mw.$(".the-icoimage").show().css('backgroundImage', 'url('+b.src+')');
        mw.$("link[rel*='icon']").attr('href', b.src);
     });
  
});

</script>




<?php

    $favicon_image =  get_option('favicon_image', 'website');
 



?>


<div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Change Favicon"); ?>
    </label>
  
  
  
  
  
    <div id="the-icoimage"></div>
    <input type="hidden" class="mw_option_field" name="favicon_image" id="favicon_image" value="<?php print $favicon_image; ?>" option-group="website" />
    <span class="pull-left the-icoimage" style="background-image: url('<?php print $favicon_image; ?>');" <?php if($favicon_image != '' and $favicon_image != false){ ?><?php } else{ ?> style="display:block;" <?php } ?>></span>
    <span class="mw-ui-btn" id="upload-icoimage"><span class="mw-icon-upload"></span><?php _e('Upload favion'); ?></span>
  </div>
  </div>






