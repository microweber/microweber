<script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<? print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("All changes are saved"); ?>.");
    });
});
</script>

<div class="<? print $config['module_class'] ?>"> website_title
  <input name="website_title" class="mw_option_field mw-ui-field"   type="text" option-group="website"  value="<? print get_option('website_title','website'); ?>" />
  website_description
  <input name="website_description" class="mw_option_field mw-ui-field"   type="text" option-group="website"  value="<? print get_option('website_description','website'); ?>" />
  website_keywords
  <input name="website_keywords" class="mw_option_field mw-ui-field"   type="text" option-group="website"  value="<? print get_option('website_keywords','website'); ?>" />
  items_per_page
  <input name="items_per_page" class="mw_option_field mw-ui-field"   type="number" option-group="website"  value="<? print get_option('items_per_page','website'); ?>" />
  <br />
  date_format
  <? $date_formats = array("Y-m-d H:i:s","m/d/y", "m/d/Y","F j, Y g:i a", "F j, Y", "F, Y", "l, F jS, Y", "M j, Y @ G:i", "Y/m/d \a\t g:i A", "Y/m/d \a\t g:ia", "Y/m/d g:i:s A", "Y/m/d", "g:i a", "g:i:s a" );  ?>
  <?   $curent_val = get_option('date_format','website'); ?>
  <select name="date_format" class="mw_option_field mw-ui-field"     option-group="website">
    <? if(isarr($date_formats )): ?>
    <? foreach($date_formats  as $item): ?>
    <option value="<? print $item ?>" <? if($curent_val == $item): ?> selected="selected" <? endif; ?>><? print date($item, time())?> - (<? print $item ?>)</option>
    <? endforeach ; ?>
    <? endif; ?>
  </select>
  
  
  <br />
<br />
time_zone
   <?   $curent_time_zone = get_option('time_zone','website'); ?>
 <? 
 
 if( $curent_time_zone == false){
	 $curent_time_zone = date_default_timezone_get(); 
 }
 
 
  $timezones = timezone_identifiers_list(); ?>
  
   <select name="time_zone" class="mw_option_field mw-ui-field"     option-group="website">
   <? foreach ($timezones as $timezone) {
  echo '<option';
  if ( $timezone == $curent_time_zone ) echo ' selected="selected"';
  echo '>' . $timezone . '</option>' . "\n";
}?>
   </select>
  

</div>
