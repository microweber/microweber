<?php defined('T') OR die(); ?>
<h1>Error</h1>
<p><?php print $e; ?></p>
<? if(isset($f) and $f !=false): ?>
<p>In <?php print str_replace(MW_ROOTPATH,'',$f); ?> 
<? if(isset($l) and $l !=false): ?>
on line <?php print $l; ?>
<? endif ?>
</p>
<? endif ?>