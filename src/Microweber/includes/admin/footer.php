</div>
<!--  /#mw-admin-container -->

<div id="create-content-menu">
  <div class="create-content-menu">
    <?php $create_content_menu = mw()->module->ui('content.create.menu'); ?>
    <?php if (!empty($create_content_menu)): ?>
    <?php foreach ($create_content_menu as $type => $item): ?>
    <?php $title = ( isset( $item['title']))? ($item['title']) : false ; ?>
    <?php $class = ( isset( $item['class']))? ($item['class']) : false ; ?>
    <?php $html = ( isset( $item['html']))? ($item['html']) : false ; ?>
    <?php $type = ( isset( $item['content_type']))? ($item['content_type']) : false ; ?>
    <?php $subtype = ( isset( $item['subtype']))? ($item['subtype']) : false ; ?>
    <a href="<?php print admin_url('view:content'); ?>#action=new:<?php print $type; ?><?php if($subtype != false): ?>.<?php print $subtype; ?><?php endif; ?>"><span class="<?php print $class; ?>"></span><strong><?php print $title; ?></strong></a>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
<div id="mobile-message" style="visibility: hidden"> <span class="mw-icon-mw"></span>
  <p class="mobile-message-paragraph">A mobile version is coming in the future!</p>
  <p class="mobile-message-paragraph">Currently Microweber is designed to be used in larger screens.</p>
  <p><span class="mw-ui-btn" onclick="mw.admin.mobileMessage(true, 'true')">Continue anyway</span></p>
</div>
</body></html>