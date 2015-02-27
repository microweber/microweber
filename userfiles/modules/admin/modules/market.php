<?php only_admin_access(); ?>
<?php $parent_module = mw()->url_manager->param('parent-module'); ?>
 

<?php
if($parent_module and isset($_GET)){
   $link_params = $_GET;
   $url = mw()->update->marketplace_link($link_params);
} else {
  $url = mw()->update->marketplace_link();	
}


 
 
  ?>

<?php if($url != false): ?>

<iframe src="<?php print $url; ?>" id="mw-update-frame" frameborder="0"></iframe>
<script>
var frame = document.getElementById('mw-update-frame');
        frame.style.height = window.innerHeight + 'px';
        frame.style.width = (window.innerWidth - document.getElementById('main-menu').offsetWidth) + 'px';

    $(window).bind('resize', function(){
        var frame = document.getElementById('mw-update-frame');
        frame.style.height = window.innerHeight + 'px';
        frame.style.width = (window.innerWidth - document.getElementById('main-menu').offsetWidth) + 'px';
    })
</script>
<?php else: ?>
<div class="mw-ui-box mw-ui-box-warn mw-ui-box-content text-center">
  <p>Cannot connect to the marketplace right now. Try again later.</p>
</div>
<?php endif; ?>
