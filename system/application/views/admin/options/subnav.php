
<div id="subheader">
  <ul>
    <li><a class="<?php if( $className == 'options' and $functionName == 'index')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/options/index')  ?>">Manage all options</a></li>
    <li><a class="<?php if( $className == 'options' and $functionName == 'add')  : ?> active<?php endif; ?>" href="<?php print site_url('admin/options/add')  ?>">Add new option</a></li>
  </ul>
</div>
