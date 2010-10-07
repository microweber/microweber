<?php dbg(__FILE__); ?>
<?php $action = $this->core_model->getParamFromURL ( 'action' ); ?>

<ul id="about-nav">
  <li  <?php if(($action == 'dashboard') or ($action == false)): ?> class="active"  <?php endif; ?>    ><a href="<?php print site_url('dashboard/'); ?>">dashboard</a></li>
  <li  <?php if($action == 'circle-of-influence'): ?> class="active"  <?php endif; ?>    ><a href="<?php print site_url('dashboard/action:circle-of-influence'); ?>">circle of influEnce</a></li>
  <li  <?php if($action == 'following'): ?> class="active"  <?php endif; ?>    ><a href="<?php print site_url('dashboard/action:following'); ?>">following</a></li>
  <li <?php if($action == 'followers'): ?> class="active"  <?php endif; ?>        ><a href="<?php print site_url('dashboard/action:followers'); ?>">followers</a></li>
  <li style="float:right"  <?php if($action == 'live'): ?> class="active title-tip" <?php else: ?>  class="title-tip" <?php endif; ?>   title="See the real time activity of all users!"       ><a href="<?php print site_url('dashboard/action:live'); ?>"><small>live feed</small></a></li>
</ul>

<?php dbg(__FILE__, 1); ?>

