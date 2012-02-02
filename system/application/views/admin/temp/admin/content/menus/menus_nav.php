<div id="subheader">
  <ul>
    <li><a class="<?php if( $className == 'menus' and $functionName == 'index')  : ?>active<?php endif; ?>" href="<?php print site_url('admin/menus')  ?>">Manage all menus</a></li>
    <li><a class="<?php if( $className == 'menus' and $functionName == 'menus_add')  : ?>active<?php endif; ?>" href="<?php print site_url('admin/menus/menus_add')  ?>">Create new menus</a></li>
  </ul>
</div>
<div id="message_area">
  <div id="message_wrapper">
    <div id="message" class="help_message">
      <?php if( $className == 'content' and $functionName == 'add')  : ?>
      Add new menu
      <?php endif; ?>
      <?php if( $className == 'menus' and (( $functionName == 'index')))  : ?>
      Edit page menus
      <?php endif; ?>
    </div>
    <!-- /message -->
  </div>
  <!-- /message_wrapper -->
</div>
<!-- /message_area -->
