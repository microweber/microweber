<? 
$get_comments_params = array();
 $get_comments_params['to_table'] = 'table_content';
if(isset($params['content-id'])){
	
	 $get_comments_params['to_table_id'] = $params['content-id'];
}

if (!isset($get_comments_params['to_table_id'])) {

 

    if (defined('POST_ID') == true and intval(POST_ID) != 0) {
       $get_comments_params['to_table_id'] = POST_ID;
    }
}
if (!isset($get_comments_params['to_table_id'])) {
    if (defined('PAGE_ID') == true) {
      $get_comments_params['to_table_id'] = PAGE_ID;
    }
}
if (!isset($get_comments_params['to_table_id'])) {

 $get_comments_params['to_table_id'] = $params['id'];
 
}
 

if(isset($params['backend']) == true): ?>
<? include('backend.php'); ?>
<? else : ?>
<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a class="active" href="javascript:;">New Comments</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
    <li><a href="javascript:;" class="">Settings</a></li>
  </ul>
  <div class="tab semi_hidden">
    <?
		
		$get_comments_params['count'] = '1';
		$get_comments_params['is_moderated'] = 'n';
		
		 ?>
    <?php $new = get_comments($get_comments_params); ?>
    <?php if($new > 0){ ?>
    <h2>You have new comments <? print $new; ?></h2>
    <a href="<?php print admin_url('view:comments'); ?>/#content_id=<? print  $get_comments_params['to_table_id']; ?>" target="_top" class="mw-ui-btn mw-ui-btn-green">See new</a>
    <?php }  else { ?>
    <?php 
	 unset($get_comments_params['is_moderated']);
		$old = get_comments($get_comments_params); ?>
    <h2>You don't have new comments <? print $old; ?></h2>
    <a href="<?php print admin_url('view:comments'); ?>/#content_id=<? print  $get_comments_params['to_table_id']; ?>" target="_top" class="mw-ui-btn">all</a>
    <?php } ?>
  </div>
  <div class="tab semi_hidden">
    <module type="admin/modules/templates"  />
  </div>
  <div class="tab semi_hidden"> 3 </div>
</div>
<? endif; ?>
