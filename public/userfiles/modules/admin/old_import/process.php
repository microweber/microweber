<?php 
$total = 0;
$remaining = 0;
$batch = mw('Microweber\Utils\Import')->batch_process();

//$batch = mw('Microweber\Utils\Import')->log();
if(isset($batch['total'])){
	$total = intval($batch['total']);
}
if(isset($batch['remaining'])){
	$remaining = intval($batch['remaining']);
}


 
?>


<?php if( $total>0 and $remaining > 0): ?>
<?php
$val2 = $total;
$val1 = $remaining;

$res = round(( $val1 / $val2) * 100);
$remaining_perc = 100-$res;
 
 ?>
<?php if($remaining > 0): ?>
Progress: <?php print $remaining_perc ?>%  
<meter value="<?php print $remaining_perc; ?>" optimum="100" high="90" low="40" max="100" min="0"><?php _e('Import progress'); ?></meter>
 <?php print $total ?>/<?php print $remaining ?>	
<?php endif ?>
<?php endif ?>