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
  <?php


  $ql = db_query_log(true) ;
  if($ql and is_array($ql) and !empty($ql))
{
	print '<b>'. count(db_query_log(true)). ' Database Queries</b>';
	foreach(db_query_log(true) as $query)
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


<b>

Cache hits <? $ch = cache_get_content_from_memory(true); print count( $ch ,COUNT_RECURSIVE ) ?></b>
  <pre><?php print_r( $ch) ?></pre>






</div>
