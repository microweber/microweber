
</div> <!--  /#mw-admin-container -->
      <menu type="context" id="mw-context-menu">
        <menuitem label="<?php _e("Add New Page"); ?>" icon="<?php print MW_INCLUDES_URL; ?>img/context.page.png" onclick="window.location.href = '<?php print admin_url(); ?>view:content#action=new:page'"></menuitem>
        <menuitem label="<?php _e("Add New Post"); ?>" icon="<?php print MW_INCLUDES_URL; ?>img/context.post.png" onclick="window.location.href = '<?php print admin_url(); ?>view:content#action=new:post'"></menuitem>
        <menuitem label="<?php _e("Add New Category"); ?>" icon="<?php print MW_INCLUDES_URL; ?>img/context.category.png" onclick="window.location.href = '<?php print admin_url(); ?>view:content#action=new:category'"></menuitem>
        <menuitem label="<?php _e("Add New Product"); ?>" icon="<?php print MW_INCLUDES_URL; ?>img/context.product.png" onclick="window.location.href = '<?php print admin_url(); ?>view:content#action=new:product'"></menuitem>
      </menu>
      <div id="create-content-menu">
  <div class="create-content-menu"> <a href="<?php print admin_url('view:content'); ?>#action=new:post"><span class="mw-icon-post"></span><strong>Post</strong></a> <a href="<?php print admin_url('view:content'); ?>#action=new:page"><span class="mw-icon-page"></span><strong>Page</strong></a> <a href="<?php print admin_url('view:content'); ?>#action=new:category"><span class="mw-icon-category"></span><strong>Category</strong></a> <a href="<?php print admin_url('view:content'); ?>#action=new:product"><span class="product-icon"><span class="product-icon-1"></span><span class="product-icon-2"></span><span class="product-icon-3"></span></span><strong>Product</strong></a> </div>
</div>


  </body>
</html>