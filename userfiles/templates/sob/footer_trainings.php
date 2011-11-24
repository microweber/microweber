</div>
<!-- /#content -->
<div id="footer">
  <div id="navigation-bar">
    <ul>
      <?php foreach($menu_items as $item): ?>
      <?php $content_id_item = $this->content_model->contentGetByIdAndCache ( $item['content_id'] );

      	  	     ?>
      <?php if($the_section_layout == false): ?>
      <li <?php if($item['is_active'] == true): ?>  class="parent parent-active" <?php else: ?> class="parent"    <?php endif; ?>>
      <a <?php if($item['is_active'] == true): ?>  class="active parent-link" <?php else: ?> class="parent-link"  <?php endif; ?> href="<?php print $item['the_url'] ?>"><span><?php print ucwords( $item['item_title'] ) ?></span><em>&nbsp;</em><strong>&nbsp;</strong></a>
      <?php $content_item = $this->content_model->contentGetById($item['content_id']) ;

		  if(!empty($content_item)){
			if(($content_item['content_subtype']  == 'blog_section' ) and (intval($content_item['content_subtype_value'])  != 0 ) ){

		//	 $view = $this->core_model->getParamFromURL ( 'view' );
$link = false;
if($view == false){
$link = $this->content_model->getContentURLById($content_item['id']).'/category:{taxonomy_value}' ;
} else {
	$link = $this->content_model->getContentURLById($content_item['id']).'/category:{taxonomy_value}/view:'. $view ;
}
$active = '  class="active"   ' ;
$actve_ids = $active_categories;
if( empty($actve_ids ) == true){
$actve_ids = array($page['content_subtype_value']);
}
$this->content_model->content_helpers_getCaregoriesUlTree($content_item['content_subtype_value'], "<a href='$link'  {active_code}    >{taxonomy_value}</a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = 'child-nav', $include_first = false);

		  }
		  }

		 ?>
      </li>
      <?php else : ?>
      <li <?php if($content_id_item['content_section_name'] == $the_section_layout): ?>  class="active"  <?php endif; ?>><a <?php if($content_id_item['content_section_name'] == $the_section_layout): ?>  class="active"  <?php endif; ?> href="<?php print $item['the_url'] ?>"><span><?php print ucwords( $item['item_title'] ) ?></span></a></li>
      <?php endif; ?>
      <?php endforeach ;  ?>

    </ul>
    </div>
  <address class="privacy-and-terms">
  <a href="<?php print $this->content_model->getContentURLByIdAndCache(217); ?>">Suggest a feature</a> | <a href="<?php print  site_url('main/rss'); ?>">RSS</a> | <a href="<?php print $this->content_model->getContentURLByIdAndCache(121); ?>">Terms and conditions</a> | <a href="<?php print $this->content_model->getContentURLByIdAndCache(122); ?>">Privacy policy</a> &copy; <?php print date('Y') ?>, SchoolofOnlineBusiness.com, Inc. or its affiliates
  </address>
   </div>
<!-- /#footer -->
</div>
<!-- /#wrapper -->
</div>
<!-- /#container -->
<span id="slidertip" style="top: 0px;left: 0px;"></span>
<div id="helpelem"></div>
<div id="footer-scripts">
  <?php include (ACTIVE_TEMPLATE_DIR.'footer_stats_scripts.php') ?>
  <?php // if(is_file(ACTIVE_TEMPLATE_DIR.'users/users_login_register_form.php') == true){ include (ACTIVE_TEMPLATE_DIR.'users/users_login_register_form.php'); } ?>
  
</div>
<!-- /#footer-scripts -->


<span id="title-tip" style="left: 0px;top: 0px;">
    <span id="title-tip-right">&nbsp;</span>
    <span id="title-tip-arr">&nbsp;</span>
    <span id="title-tip-content"></span>
</span>
<div id="preloader" style="top: 0px;left:0px;">
    <div id="preloader-content">&nbsp;</div>

</div>
<div id="Helper" style="top: 0px;left: 0px;">&nbsp;</div>

<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/before.ready.js"></script>  
</body></html>