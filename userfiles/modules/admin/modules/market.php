<?php must_have_access(); ?>
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

<iframe src="<?php print $url; ?>" id="mw-update-frame" frameborder="0" class="w-100" style="width: 100%; position: relative;"></iframe>
<script>

    var frameSize = function (){
        var frame = document.getElementById('mw-update-frame');
        frame.style.height = window.innerHeight + 'px';
        var menu = document.getElementById('mw-admin-main-menu');
        frame.style.width = (window.innerWidth - (menu ? menu.offsetWidth : 0)) + 'px';
    };

    frameSize();

    addEventListener('resize', function(){
        frameSize();
    })
</script>
<?php else: ?>
<div class="mw-ui-box mw-ui-box-warn mw-ui-box-content text-center">
  <p>Cannot connect to the marketplace right now. Try again later.</p>
</div>
<?php endif; ?>
