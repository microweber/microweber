<script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<? print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("All changes are saved"); ?>.");
    });
});
</script>

<div class="<? print $config['module_class'] ?>">
<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    Website Name <br>
    <small>
        This is very important for the Search Engines.
        Your website will be categorized by many criterias and the Name is one of it.
        <a href="javascript:;" class="mw-ui-link" onclick="mw.help('website_name');">See the example</a>
    </small>
  </label>
  <input name="website_title" class="mw_option_field mw-ui-field"   type="text" option-group="website"  value="<? print get_option('website_title','website'); ?>" />
  </div>
  <div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    Website Description <br>
    <small>Describe what is your website for in short descriprion.</small>
  </label>
  <textarea name="website_description" class="mw_option_field mw-ui-field"   type="text" option-group="website"><? print get_option('website_description','website'); ?></textarea>
</div>


<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    Posts per Page <br>
    <small>Select how many posts or products you want to have per page? example 10,25,50...</small>
  </label>
   <div class="mw-ui-select" style="min-width:85px;">
   <select  name="items_per_page" class="mw_option_field"   type="range" option-group="website" >

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
  </div>



<div class="mw-ui-field-holder">
 <label class="mw-ui-label">
    Website Keywords  <br>
    <small>Ex. Cat, Videos of Cats, Funny Cats, Cat Pictures, Cat for Sale, Cat Products and Food</small>
 </label>
  <input name="website_keywords" class="mw_option_field mw-ui-field"   type="text" option-group="website"  value="<? print get_option('website_keywords','website'); ?>" />
</div>


  <div class="mw-ui-field-holder">
  <label class="mw-ui-label">Date Format</label>
  <? $date_formats = array("Y-m-d H:i:s","m/d/y", "m/d/Y","F j, Y g:i a", "F j, Y", "F, Y", "l, F jS, Y", "M j, Y @ G:i", "Y/m/d \a\t g:i A", "Y/m/d \a\t g:ia", "Y/m/d g:i:s A", "Y/m/d", "g:i a", "g:i:s a" );  ?>
  <?   $curent_val = get_option('date_format','website'); ?>

  <div class="mw-ui-select" style="width: 300px;">
  <select name="date_format" class="mw_option_field"     option-group="website">
    <? if(isarr($date_formats )): ?>
    <? foreach($date_formats  as $item): ?>
    <option value="<? print $item ?>" <? if($curent_val == $item): ?> selected="selected" <? endif; ?>><? print date($item, time())?> - (<? print $item ?>)</option>
    <? endforeach ; ?>
    <? endif; ?>
  </select>
  </div>
  </div>

<div class="mw-ui-field-holder">
<label class="mw-ui-label">Time Zone</label>
   <?   $curent_time_zone = get_option('time_zone','website'); ?>
 <? 
 
 if( $curent_time_zone == false){
	 $curent_time_zone = date_default_timezone_get();
 }
 
 
  $timezones = timezone_identifiers_list(); ?>
    <div class="mw-ui-select" style="width: 300px;">
   <select name="time_zone" class="mw_option_field" option-group="website">
   <? foreach ($timezones as $timezone) {
  echo '<option';
  if ( $timezone == $curent_time_zone ) echo ' selected="selected"';
  echo '>' . $timezone . '</option>' . "\n";
}?>
   </select>
    </div>

</div>
</div>
