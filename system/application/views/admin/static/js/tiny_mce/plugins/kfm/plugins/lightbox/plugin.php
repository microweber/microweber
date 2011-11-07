<?php
$p=new kfmPlugin('lightbox');
$p->addSetting('lightbox_slideshow_delay',array('type'=>'integer', 'unit'=>'s', 'properties'=>array('size'=>3)),4);
$p->addJavascript('var lightbox_slideshow_delay={$setting.lightbox_slideshow_delay}*1000;');
$kfm->addPlugin($p);
?>
