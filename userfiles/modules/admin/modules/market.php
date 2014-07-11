<?php only_admin_access(); ?>
<?php $url = mw()->update->marketplace_link(); ?>
 
<?php if($url != false): ?>

<iframe src="<?php print $url; ?>" width="100%" height="1000" frameborder="0"></iframe>
<?php else: ?>
<div class="mw-ui-box mw-ui-box-warn mw-ui-box-content text-center"> 
  <p>Cannot connect to the marketplace right now. Try again later.</p>
</div>
<?php endif; ?>
