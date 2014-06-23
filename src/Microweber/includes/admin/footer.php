</div> <!--  /#mw-admin-container -->
<div id="create-content-menu">
    <div class="create-content-menu">
        <?php $create_content_menu = mw()->ui->create_content_menu(); ?>
        <?php if (!empty($create_content_menu)): ?>
            <?php foreach ($create_content_menu as $type => $item): ?>
                <a href="<?php print admin_url('view:content'); ?>#action=new:<?php print $type; ?>"><span
                        class="mw-icon-<?php print $type; ?>"></span><strong><?php print $item; ?></strong></a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div id="mobile-message" style="visibility: hidden">

<span class="mw-icon-mw"></span>

<p class="mobile-message-paragraph">A mobile version is coming in the future!</p>
<p class="mobile-message-paragraph">Currently Microweber is designed to be used in larger screens.</p>


<p><span class="mw-ui-btn" onclick="mw.admin.mobileMessage(true, 'true')">Continue anyway</span></p>

</div>


</body>
</html>