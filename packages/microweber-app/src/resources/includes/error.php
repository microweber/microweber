 
<h1><?php print ("Error"); ?></h1>
<p><?php print $e; ?></p>
<?php if(isset($f) and $f !=false): ?>
<p><?php print ("In"); ?> <?php print ($f); ?>
<?php if(isset($l) and $l !=false): ?>
<?php print ("on line"); ?> <?php print $l; ?>
<?php endif ?>
</p>
<?php endif ?>