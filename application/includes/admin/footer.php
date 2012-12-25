</div> <!--  /#mw-admin-content  -->

<div id="footer">

    <div id="footer-content">
        <div id="PowerdBy">
            <a href="http://microweber.com" target="_blank" id="PowerdByLink" title="<?php _e("Powered by Microweber"); ?>"><?php _e("(MW)"); ?></a>
            <div id="PowerdByInfo">Powered By <a target="_blank" href="http://microweber.com">Microweber</a></div>
        </div>
    </div>

</div>


</div> <!--  /#mw-admin-container -->



 <menu type="context" id="mw-context-menu">

      <menuitem label="Add New Page" icon="<? print INCLUDES_URL; ?>img/context.page.png" onclick="window.location.href = '<?php print admin_url(); ?>view:content#action=new:page'"></menuitem>
      <menuitem label="Add New Post" icon="<? print INCLUDES_URL; ?>img/context.post.png" onclick="window.location.href = '<?php print admin_url(); ?>view:content#action=new:post'"></menuitem>
      <menuitem label="Add New Category" icon="<? print INCLUDES_URL; ?>img/context.category.png" onclick="window.location.href = '<?php print admin_url(); ?>view:content#action=new:category'"></menuitem>
      <menuitem label="Add New Product" icon="<? print INCLUDES_URL; ?>img/context.product.png" onclick="window.location.href = '<?php print admin_url(); ?>view:content#action=new:product'"></menuitem>

 </menu>
<?php



/* <a href="<? print site_url('api/set_language/en') ?>">en</a>
<a href="<? print site_url('api/set_language/bg') ?>">bg</a>  */

?>
<div id="mw-notifications-holder"></div>
  </body>
</html>