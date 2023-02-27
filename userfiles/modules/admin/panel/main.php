<div class="mw-admin-main-panel-top-bar mw-admin-main-panel-top-bar-right"></div>
<div class="mw-admin-big-spacer"></div>
<?php $action = url_param('action');




$vf = false;
if ($action) {
    $vf = __DIR__.DS.$action. '.php';
    $vf = sanitize_path($vf);
}
?>
<div class="mw-admin-main-view-container mw-ui-admin">
  <?php
 if(is_file($vf)){

         include ($vf);

        } else {
		print 'main';
		}

 ?>
</div>
