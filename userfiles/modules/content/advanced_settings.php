<?php 
only_admin_access();
$data = false;
if(isset($params['content-id'])){
  $data = mw('content')->get_by_id(intval($params["content-id"]));
}


 
/* FILLING UP EMPTY CONTENT WITH DATA */
if($data == false or empty($data )){
   $is_new_content = true;
   include('_empty_content_data.php');
}
/* END OF FILLING UP EMPTY CONTENT  */
$show_page_settings = false;
if(isset($params['content-type']) and $params['content-type'] == 'page'){
$show_page_settings = 1;	
}
 

?>

<div class="vSpace"></div>
<div class="mw-ui-field-holder">
	<label class="mw-ui-label">
		<?php _e("Description"); ?>
		<small class="mw-help" data-help="Short description for yor content.">(?)</small></label>
	<textarea
				class="mw-ui-field" name="description"   placeholder="<?php _e("Describe your page in short"); ?>"><?php if($data['description']!='') print ($data['description'])?>
		</textarea>
</div>
<div class="mw-ui-field-holder">
	<label class="mw-ui-label">
		<?php _e("Meta Title"); ?>
		<small class="mw-help" data-help="Title for this <?php print $data['content_type'] ?> that will appear on the search engines on social networks.">(?)</small></label>
	<textarea class="mw-ui-field" name="content_meta_title"  placeholder="<?php _e("Title to appear on the search engines results page"); ?>."><?php if(isset($data['content_meta_title']) and $data['content_meta_title']!='') print ($data['content_meta_title'])?>
		</textarea>
</div>
<div class="mw-ui-field-holder">
	<label class="mw-ui-label">
		<?php _e("Meta Keywords"); ?>
		<small class="mw-help" data-help="Keywords for this <?php print $data['content_type'] ?> that will help the search engines to find it. Ex: ipad, book, tutorial">(?)</small></label>
	<textarea class="mw-ui-field" name="content_meta_keywords"  placeholder="<?php _e("Type keywords that describe your content - Example: Blog, Online News, Phones for Sale etc"); ?>."><?php if(isset($data['content_meta_keywords']) and $data['content_meta_keywords']!='') print ($data['content_meta_keywords'])?>
		</textarea>
</div>
<div class="vSpace"></div>
<div class="vSpace"></div>
<div class="mw_clear vSpace"></div>
<?php if($show_page_settings != false): ?>
<div class="mw-ui-check-selector">
	<div class="mw-ui-label left" style="width: 130px">
		<?php _e("Is Home"); ?>
		?</div>
	<label class="mw-ui-check">
		<input name="is_home" type="radio"  value="n" <?php if( '' == trim($data['is_home']) or 'n' == trim($data['is_home'])): ?>   checked="checked"  <?php endif; ?> />
		<span></span><span>
		<?php _e("No"); ?>
		</span></label>
	<label class="mw-ui-check">
		<input name="is_home" type="radio"  value="y" <?php if( 'y' == trim($data['is_home'])): ?>   checked="checked"  <?php endif; ?> />
		<span></span><span>
		<?php _e("Yes"); ?>
		</span></label>
</div>
<div class="mw_clear vSpace"></div>
<div class="mw-ui-check-selector">
	<div class="mw-ui-label left" style="width: 130px">
		<?php _e("Is Shop"); ?>
		<small class="mw-help" data-help="<?php _e("If yes this page will accept products to be added to it"); ?>">(?)</small></div>
	<label class="mw-ui-check">
		<input name="is_shop"  type="radio"  value="n" <?php if( '' == trim($data['is_shop']) or 'n' == trim($data['is_shop'])): ?>   checked="checked"  <?php endif; ?> />
		<span></span><span>
		<?php _e("No"); ?>
		</span></label>
	<label class="mw-ui-check">
		<input name="is_shop" type="radio"  value="y" <?php if( 'y' == trim($data['is_shop'])): ?>   checked="checked"  <?php endif; ?> />
		<span></span><span>
		<?php _e("Yes"); ?>
		</span></label>
</div>
<div class="mw_clear vSpace"></div>
<?php endif; ?>
<?php  if(isset($data['position'])): ?>
<input name="position"  type="hidden" value="<?php print ($data['position'])?>" />
<?php endif; ?>
<?php /* PAGES ONLY  */ ?>
<?php event_trigger('mw_admin_edit_page_advanced_settings', $data); ?>
<?php if(isset($data['id']) and $data['id'] > 0): ?>
<br />
<small>
<?php _e("Id"); ?>
: <?php print ($data['id'])?></small> 
<script  type="text/javascript">
		 
		
		mw.del_curent_page = function(a, callback){
			mw.tools.confirm("<?php _e("Are you sure you want to delete this"); ?>?", function(){
				var arr = (a.constructor === [].constructor) ? a : [a];
				var obj = {ids:arr}
				$.post(mw.settings.site_url + "api/content/delete", obj, function(data){
				   mw.notification.warning("<?php _e('Content was sent to Trash'); ?>.");
				   typeof callback === 'function' ? callback.call(data) : '';
				});
			});
		}
		
		</script> 
<small><a href="javascript:mw.del_curent_page('<?php print ($data['id'])?>');"  class="mw-ui-link">
<?php _e('Delete'); ?>
</a></small>
<?php endif; ?>
<?php if(isset($data['created_on'])): ?>
<br />
<small>
<?php _e("Created on"); ?>
: <?php print mw('format')->date($data['created_on'])?></small>
<?php endif; ?>
<?php if(isset($data['created_on'])): ?>
<br />
<small>
<?php _e("Updated on"); ?>
: <?php print mw('format')->date($data['updated_on'])?></small>
<?php endif; ?>
<?php /* PRODUCTS ONLY  */ ?>
