<?php dbg(__FILE__); ?>
<?php $action = $this->core_model->getParamFromURL ( 'action' ); ?>

<ul id="about-nav">
<li  <?php if($show == 'unread'): ?> class="active"  <?php endif; ?>    ><a href="<?php print site_url('dashboard/action:messages/show:unread'); ?>">Unread messages</a></li> 
  <li  <?php if(($show == 'read') or ($action == false)): ?> class="active"  <?php endif; ?>    ><a href="<?php print site_url('dashboard/action:messages/show:read'); ?>">Read messages</a></li>
  
   <?php if($show == 'conversation'): ?> <li   class="active"    ><a href="">Conversation</a></li>   <?php endif; ?> 
  
</ul>
<?php dbg(__FILE__, 1); ?>
