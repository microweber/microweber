<div class="footer">
  <editable rel="global" field="footer_links">
  <div class="footer_blk_1">
    <div class="footer_subhead">How it works</div>
    <? 
	$params = array();
	$params['content_parent'] = 3477;
		$params['content_type'] = 'page';

	
	
	//$pages = get_pages($params);
	$pages = CI::model ( 'content' )->getContentAndCache ( $params );
	// p($pages);
	?>
    <? foreach($pages as $pag): ?>
    <a href="<? print page_link($pag['id']); ?>"><? print ucfirst($pag['content_title']) ?></a>
    <? endforeach; ?>
  </div>
  <div class="footer_blk_2">
    <div class="footer_subhead">About us</div>
    <? 
	$params = array();
	$params['content_parent'] = 3502;
		$params['content_type'] = 'page';

	
	
	//$pages = get_pages($params);
	$pages = CI::model ( 'content' )->getContentAndCache ( $params );
	// p($pages);
	?>
    <? foreach($pages as $pag): ?>
    <a href="<? print page_link($pag['id']); ?>"><? print ucfirst($pag['content_title']) ?></a>
    <? endforeach; ?>
  </div>
  <div class="footer_blk_3">
    <div class="footer_subhead">The Kewl Stuff</div>
    <? 
	$params = array();
	$params['content_parent'] = 3480;
		$params['content_type'] = 'page';

	
	
	//$pages = get_pages($params);
	$pages = CI::model ( 'content' )->getContentAndCache ( $params );
	// p($pages);
	?>
    <? foreach($pages as $pag): ?>
    <a href="<? print page_link($pag['id']); ?>"><? print ucfirst($pag['content_title']) ?></a>
    <? endforeach; ?>
  </div>
  <div class="footer_blk_4">
    <div class="footer_subhead">More links</div>
    <a href="#">Some link 1</a> <a href="#">Some link 2</a> <a href="#">Some link 3</a> <a href="#">Some link 5</a> <a href="#">More...</a> </div>
  <div class="footer_blk_5">
    <div class="footer_subhead">More</div>
    <a href="<? print site_url('forum') ?>">Forum</a> <a href="http://www.2studyfoundation.org.uk/">2Study Foundation</a> </div>
</div>
</editable>
</div>
<div class="icons">
  <div class="f_icon"><a href="https://www.facebook.com/pages/Money2Studycom/231996293479013" target="_blank"><img src="<? print TEMPLATE_URL ?>images/f_icon.png" border="0" alt="f" /></a></div>
  <div class="t_icon"><a href="https://twitter.com/Money2Study" target="_blank"><img src="<? print TEMPLATE_URL ?>images/t_icon.png" border="0" alt="t" /></a></div>
</div>
</div>
</div>
<div class="bot_line" align="center">
  <div class="bot_container">
    <div class="bot_left">&copy; All rights reserved 2011-<? print date("Y") ?> <a href="<? print site_url(); ?>">Money2Study.com</a>.</div>
    <div class="bot_rt"> <a href="<? print site_url('terms-and-conditions'); ?>">Terms and conditions</a> | <a href="<? print site_url('privacy-policty'); ?>">Privacy policy</a> | <a href="<? print site_url('faq'); ?>">FAQ </a> </div>
  </div>
</div>
</div>
 
<script type="text/javascript"> Cufon.now(); </script>
</body></html>