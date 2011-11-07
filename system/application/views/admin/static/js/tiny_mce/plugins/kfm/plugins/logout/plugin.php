<?php
$p=new kfmPlugin('logout');
$p->adminTab('admin_logout.php', array('title' => "Logout"));
$kfm->addPlugin($p);
?>
