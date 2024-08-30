<?php defined('MW_VERSION') OR die(); ?>

<div style="margin: 5em 0;padding:2em;background:#ECF5FA;color:#000;clear:both;"> <b><?php _e('Benchmarks'); ?></b>
  <pre>
  
  
<?php 
/* var_dump(debug_backtrace());
$arr = get_defined_functions();

print_r($arr['user']);*/

$mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - T); 
   echo "This page was created in ".$totaltime." seconds"; 


//print (round(microtime()-T,5)*1000); ?>  
<?php // print number_format(memory_get_usage()-M); ?> bytes
<?php print number_format(memory_get_usage()); ?> bytes (process)
<?php print number_format(memory_get_peak_usage(TRUE)); ?> bytes (process peak)
</pre>
  <b>URL</b>
  <pre><?php print implode('/',mw()->url_manager->segment()); ?></pre>
  <?php


   $ql = \mw()->database_manager->query_log(true) ;
  if($ql and is_array($ql) and !empty($ql))
    {
    	print '<b>'. count(\mw()->database_manager->query_log(true)). ' Database Queries</b>';
    	foreach(\mw()->database_manager->query_log(true) as $query)
    	{
    		print '<pre style="background:#fff">'. $query. '</pre>';
    	}
    }
 


?>
    <b>Debug</b>
    <pre><?php print print_r(mwdbg(true)); ?></pre>
  <?php if(!(mw()->user_manager->session_all() == false)) { ?>
  <b>Session Data</b> <?php print '<pre>';print_r($_SESSION);print '</pre>'; ?>
  <?php } ?>
  <?php $included_files = get_included_files(); ?>
  <b><?php print count($included_files); ?> PHP Files Included:</b>
  <pre>
<?php print implode("\n", $included_files); ?>
</pre>


<b>

Cache hits <?php $ch = mw()->cache_manager->debug(true); print count( $ch ,COUNT_RECURSIVE ) ?></b>
  <pre><?php print_r( $ch) ?></pre>
 


</div>
