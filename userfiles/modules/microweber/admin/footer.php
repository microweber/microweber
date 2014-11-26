</div>
<!--  /#mw-admin-container -->
<?php $custom_view = url_param('view'); ?>
<?php $custom_action = url_param('action'); ?>



<div id="create-content-menu">
  <div class="create-content-menu">
       <?php   event_trigger('content.create.menu'); ?>
     <?php $create_content_menu = mw()->modules->ui('content.create.menu'); ?>
    <?php if (!empty($create_content_menu)): ?>
    <?php foreach ($create_content_menu as $type => $item): ?>
    <?php $title = ( isset( $item['title'])) ? ($item['title']) : false ; ?>
    <?php $class = ( isset( $item['class'])) ? ($item['class']) : false ; ?>
    <?php $html = ( isset( $item['html'])) ? ($item['html']) : false ; ?>
    <?php $type = ( isset( $item['content_type'])) ? ($item['content_type']) : false ; ?>
    <?php $subtype = ( isset( $item['subtype'])) ? ($item['subtype']) : false ; ?>
    <?php $base_url = ( isset( $item['base_url'])) ? ($item['base_url']) : false ; ?>
    <?php 
	
	if($base_url  == false){
		$base_url = admin_url('view:content');
		if($custom_action != false){
			if($custom_action == 'pages' or $custom_action == 'posts' or $custom_action == 'products'){
				$base_url = $base_url.'/action:'.$custom_action;
			}
		}
	} 
	
	?>
    

    <a href="<?php print $base_url; ?>#action=new:<?php print $type; ?><?php if($subtype != false): ?>.<?php print $subtype; ?><?php endif; ?>"><span class="<?php print $class; ?>"></span><strong><?php print $title; ?></strong></a>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
<div id="mobile-message" style="visibility: hidden; display: none !important;"> <span class="mw-icon-mw"></span>
  <p class="mobile-message-paragraph">A mobile version is coming in the future!</p>
  <p class="mobile-message-paragraph">Currently Microweber is designed to be used in larger screens.</p>
  <p><span class="mw-ui-btn" onclick="mw.admin.mobileMessage(true, 'true')">Continue anyway</span></p>
</div>
</body></html>