<?php defined('T') OR die(); ?>

<div style="margin: 5em 0;padding:2em;background:#ECF5FA;color:#000;clear:both;"> <b>Benchmarks</b>
  <pre>
<?php print (round(microtime()-T,5)*1000); ?> ms
<?php print number_format(memory_get_usage()-M); ?> bytes
<?php print number_format(memory_get_usage()); ?> bytes (process)
<?php print number_format(memory_get_peak_usage(TRUE)); ?> bytes (process peak)
</pre>
  <b>URL</b>
  <pre><?php print implode('/',url()); ?></pre>
  <?php if(class_exists('DB', FALSE)&&DB::$q)
{
	print '<b>'. count(DB::$q). ' Database Queries</b>';
	foreach(DB::$q as $query)
	{
		print '<pre style="background:#fff">'. $query. '</pre>';
	}
}
?>
  <?php if(!empty($_SESSION)) { ?>
  <b>Session Data</b> <?php print '<pre>';print_r($_SESSION);print '</pre>'; ?>
  <?php } ?>
  <?php $included_files = get_included_files(); ?>
  <b><?php print count($included_files); ?> PHP Files Included:</b>
  <pre>
<?php print implode("\n", $included_files); ?>
 
</pre>
</div>
