

<? if(is_admin() != false): ?>



<a href="<?php print admin_url(); ?>view:dashboard">dashboard</a>

<a href="<?php print admin_url(); ?>view:content">content</a>
<a href="<?php print admin_url(); ?>view:shop">Online shop</a>

<a href="<?php print admin_url(); ?>view:modules">modules</a>
<a href="<?php print admin_url(); ?>view:elements">elements</a>
<a href="<?php print admin_url(); ?>view:updates">updates</a>


<a href="<?php print admin_url(); ?>view:settings">settings</a>




|
<a href="<?php print api_url('logout'); ?>">logout</a>


<? endif; ?>



