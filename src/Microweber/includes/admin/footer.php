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


</body>
</html>