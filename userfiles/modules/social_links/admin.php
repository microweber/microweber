<?php only_admin_access(); ?>



<?php

$option_group=$params['id'];

if(isset($params['option-group'])){
$option_group=$params['option-group'];	
	
}




    $facebook_enabled = get_option('facebook_enabled', $option_group) == 'y';
    $twitter_enabled = get_option('twitter_enabled', $option_group) == 'y';
    $googleplus_enabled = get_option('googleplus_enabled', $option_group) == 'y';
    $pinterest_enabled = get_option('pinterest_enabled', $option_group) == 'y';
    $youtube_enabled = get_option('youtube_enabled', $option_group) == 'y';
    $linkedin_enabled = get_option('linkedin_enabled', $option_group) == 'y';
    $instagram_enabled = get_option('instagram_enabled', $option_group) == 'y';

    $instagram_url =  get_option('instagram_url', $option_group);
    $facebook_url =  get_option('facebook_url', $option_group);
    $twitter_url =  get_option('twitter_url', $option_group);
    $googleplus_url =  get_option('googleplus_url', $option_group);
    $pinterest_url =  get_option('pinterest_url', $option_group);
    $youtube_url =  get_option('youtube_url', $option_group);
    $linkedin_url =  get_option('linkedin_url', $option_group);


?>


<?php



?>


<div class="module-live-edit-settings">
<style scoped="scoped">

.module-social-links-settings-table .mw-ui-inline-label{
  margin-right:0;
  padding-right: 3px;
  width: 130px;
  text-align: right;
}

.module-social-links-settings-table [class*='mw-icon-']{
  font-size: 27px;
}

</style>

<table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic module-social-links-settings-table">
  <thead>
    <tr>
        <th>Icon </th>
        <th>Enabled </th>
        <th>URL</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><span class="mw-icon-facebook"></span></td>
      <td><label class="mw-ui-check">
                <input type="checkbox" class="mw_option_field" option-group="<?php print $option_group; ?>" name="facebook_enabled" value="y" <?php if($facebook_enabled) print 'checked="checked"'; ?>><span></span>
            </label></td>
      <td><label class="mw-ui-inline-label">facebook.com/</label><input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="facebook_url" value="<?php print $facebook_url; ?>" /></td>
    </tr>
    <tr>
      <td><span class="mw-icon-twitter"></span></td>
      <td><label class="mw-ui-check">
                <input type="checkbox" class="mw_option_field" name="twitter_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if($twitter_enabled) print 'checked="checked"'; ?>><span></span>
            </label></td>
      <td><label class="mw-ui-inline-label">twitter.com/</label><input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="twitter_url"  value="<?php print $twitter_url; ?>" /></td>
    </tr>

    <tr>
      <td><span class="mw-icon-googleplus"></span></td>
      <td><label class="mw-ui-check">
                <input type="checkbox" class="mw_option_field" name="googleplus_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if($googleplus_enabled) print 'checked="checked"'; ?>><span></span>
            </label></td>
      <td><label class="mw-ui-inline-label">plus.google.com/</label><input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="googleplus_url"  value="<?php print $googleplus_url; ?>" /></td>
    </tr>

    <tr>
      <td><span class="mw-icon-social-pinterest"></span></td>
      <td><label class="mw-ui-check">
                <input type="checkbox" class="mw_option_field" name="pinterest_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if($pinterest_enabled) print 'checked="checked"'; ?>><span></span>
            </label></td>
      <td><label class="mw-ui-inline-label">pinterest.com/</label><input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="pinterest_url"  value="<?php print $pinterest_url; ?>" /></td>
    </tr>

    <tr>
      <td><span class="mw-icon-social-youtube"></span></td>
      <td><label class="mw-ui-check">
                <input type="checkbox" class="mw_option_field" name="youtube_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if($youtube_enabled) print 'checked="checked"'; ?>><span></span>
            </label></td>
      <td><label class="mw-ui-inline-label">youtube.com/</label><input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="youtube_url"  value="<?php print $youtube_url; ?>" /></td>
    </tr>
    <tr>
      <td><span class="mw-icon-social-linkedin"></span></td>
      <td><label class="mw-ui-check">
                <input type="checkbox" class="mw_option_field" name="linkedin_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if($linkedin_enabled) print 'checked="checked"'; ?>><span></span>
            </label></td>
      <td><label class="mw-ui-inline-label">linkedin.com/</label><input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="linkedin_url"  value="<?php print $linkedin_url; ?>" /></td>
    </tr>
    <tr>
      <td><span class="mw-icon-social-instagram"></span></td>
      <td><label class="mw-ui-check">
                <input type="checkbox" class="mw_option_field" name="instagram_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if($instagram_enabled) print 'checked="checked"'; ?>><span></span>
            </label></td>
      <td><label class="mw-ui-inline-label">instagram.com/</label><input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="instagram_url"  value="<?php print $instagram_url; ?>" /></td>
    </tr>
  </tbody>
</table>


</div>