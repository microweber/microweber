<?php

/*

type: layout

name: user layout

description: user site layout









*/



?>
<?php // include "dashboard/layout.php" ?>
<? if($post): ?>
<? $redir = site_url('dashboard/action:my-videos/id:'.$post['id'].'/user_id:'.$post['created_by']);?>
<meta http-equiv="refresh" content="0;URL=<? print $redir ?>" />
 <? endif; ?>